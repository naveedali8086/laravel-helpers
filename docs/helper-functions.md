# Helper Functions

## `remove_rule()`

Remove one or more validation rules from a rule set without modifying the original validation logic.

### Function Signature


```php
remove_rule(string|array attributeRules, arrayrulesToRemove): string|array 
```

### Parameters

- **`$attributeRules`** - Validation rules in string format (`'required|email'`) or array format (`['required', 'email']`)
- **`$rulesToRemove`** - Array of rule names to remove (e.g., `['unique', 'email']`)

### Returns

Same format as input (string or array) with specified rules removed

---

## Basic Usage

### String Format
```php
// Remove a single rule
rules = 'required|email|unique:users|max:255';result = remove_rule($rules, ['unique']); // Result: 'required|email|max:255'

// Remove multiple rules
rules = 'required|email|unique:users|max:255|min:3';result = remove_rule($rules, ['unique', 'min']); // Result: 'required|email|max:255'
```

### Array Format

```php
// Remove a single rule
$rules = ['required', 'email', 'unique:users', 'max:255'];
$result = remove_rule($rules, ['unique']);
// Result: ['required', 'email', 'max:255']

// Remove multiple rules
$rules = ['required', 'email', 'unique:users', 'max:255', 'min:3'];
$result = remove_rule($rules, ['unique', 'min']);
// Result: ['required', 'email', 'max:255']
```


 
## Advanced Usage

### Working with Object Rules

```php
use Illuminate\Validation\Rules\Unique;

$rules = [
    'required',
    new Unique('users', 'email'),
    'max:255'
];

$result = remove_rule($rules, ['unique']);
// Result: ['required', 'max:255']
```


### Complex Rules with Parameters

``` php
// Works with rules containing parameters
$rules = 'required|unique:users,email,1,id|in:admin,user,moderator|regex:/^[a-z]+$/';
$result = remove_rule($rules, ['unique', 'regex']);
// Result: 'required|in:admin,user,moderator'
```

### Edge Cases

```php
// Empty rules
$result = remove_rule('', ['required']);
// Result: ''

// No matching rules
$result = remove_rule('required|email', ['unique']);
// Result: 'required|email'

// All rules removed
$result = remove_rule('required|email', ['required', 'email']);
// Result: ''
```


### See Also
[Usage Examples - Real-world scenarios](usage-examples.md)

