# Usage Examples
Real-world examples of how to use Laravel Helpers in your projects.

## Reusing Validation Rules Between Store and Update
One of the most common use cases is sharing validation rules between create and update operations.

### Example: User CRUD Operations

```php
class UserController extends Controller
{
    protected function getValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function store(Request $request)
    {
        // Use all rules for creating new user
        $validated = $request->validate($this->getValidationRules());
        
        User::create($validated);
        
        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $rules = $this->getValidationRules();
        
        // Remove unique check and re-add with exception for current user
        $rules['email'] = remove_rule($rules['email'], ['unique']);
        $rules['email'] .= '|unique:users,email,' . $user->id;
        
        // Make password optional for updates
        $rules['password'] = remove_rule($rules['password'], ['required']);
        $rules['password'] = 'nullable|' . $rules['password'];
        
        $validated = $request->validate($rules);
        
        if (!isset($validated['password'])) {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return response()->json([
            'message' => 'User updated successfully'
        ]);
    }
```

## Form Request Inheritance
Create a base form request and extend it for different operations.
```php
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users|max:255',
            'username' => 'required|string|unique:users|max:50',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $storeRules = (new StoreUserRequest())->rules();
        
        return [
            // Make email optional and remove uniqueness
            'email' => 'sometimes|' . remove_rule($storeRules['email'], ['required', 'unique']),
            
            // Make username optional and remove uniqueness
            'username' => 'sometimes|' . remove_rule($storeRules['username'], ['required', 'unique']),
            
            // Make password optional
            'password' => 'sometimes|' . remove_rule($storeRules['password'], ['required']),
        ];
    }
}
```

### API Resource with Conditional Validation
```php
class ProductController extends Controller
{
    protected function getBaseRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_published' => 'required|boolean',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->getBaseRules());
        
        $product = Product::create($validated);
        
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $rules = $this->getBaseRules();
        
        // Remove required constraints for partial updates
        foreach ($rules as $field => $rule) {
            $rules[$field] = 'sometimes|' . remove_rule($rule, ['required']);
        }
        
        // Handle unique SKU
        $rules['sku'] = remove_rule($rules['sku'], ['unique']);
        $rules['sku'] .= '|unique:products,sku,' . $product->id;
        
        $validated = $request->validate($rules);
        
        $product->update($validated);
        
        return response()->json($product);
    }

    public function quickUpdate(Request $request, Product $product)
    {
        $rules = $this->getBaseRules();
        
        // Allow only price and stock updates
        $allowedFields = ['price', 'stock'];
        $rules = array_intersect_key($rules, array_flip($allowedFields));
        
        // Make both fields optional
        foreach ($rules as $field => $rule) {
            $rules[$field] = 'sometimes|' . remove_rule($rule, ['required']);
        }
        
        $validated = $request->validate($rules);
        
        $product->update($validated);
        
        return response()->json($product);
    }
}
```

## Batch Operations with Flexible Validation

```php
class BulkUserUpdateController extends Controller
{
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*.id' => 'required|exists:users,id',
        ]);
        
        $baseRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|in:active,inactive',
        ];
        
        $results = [];
        
        foreach ($request->users as $userData) {
            $rules = $baseRules;
            
            // Make all fields optional for bulk updates
            foreach ($rules as $field => $rule) {
                $rules[$field] = 'sometimes|' . remove_rule($rule, ['required']);
            }
            
            // Handle unique email for specific user
            if (isset($userData['email'])) {
                $rules['email'] = remove_rule($rules['email'], ['unique']);
                $rules['email'] .= '|unique:users,email,' . $userData['id'];
            }
            
            $validator = Validator::make($userData, $rules);
            
            if ($validator->fails()) {
                $results[] = [
                    'id' => $userData['id'],
                    'success' => false,
                    'errors' => $validator->errors()
                ];
                continue;
            }
            
            $user = User::find($userData['id']);
            $user->update($validator->validated());
            
            $results[] = [
                'id' => $userData['id'],
                'success' => true
            ];
        }
        
        return response()->json(['results' => $results]);
    }
}
```