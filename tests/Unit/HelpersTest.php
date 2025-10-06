
<?php

namespace Naveedali8086\LaravelHelpers\Tests;

use Illuminate\Validation\Rules\Unique;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_removes_single_rule_from_string_format()
    {
        $rules = 'required|email|unique:users|max:255';
        $result = remove_rule($rules, ['unique']);
        
        $this->assertEquals('required|email|max:255', $result);
    }

    /** @test */
    public function it_removes_multiple_rules_from_string_format()
    {
        $rules = 'required|email|unique:users|max:255|min:3';
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertEquals('required|email|max:255', $result);
    }

    /** @test */
    public function it_removes_single_rule_from_array_format()
    {
        $rules = ['required', 'email', 'unique:users', 'max:255'];
        $result = remove_rule($rules, ['unique']);
        
        $this->assertEquals(['required', 'email', 'max:255'], $result);
    }

    /** @test */
    public function it_removes_multiple_rules_from_array_format()
    {
        $rules = ['required', 'email', 'unique:users', 'max:255', 'min:3'];
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertEquals(['required', 'email', 'max:255'], $result);
    }

    /** @test */
    public function it_removes_rule_with_parameters()
    {
        $rules = 'required|unique:users,email,1|max:255';
        $result = remove_rule($rules, ['unique']);
        
        $this->assertEquals('required|max:255', $result);
    }

    /** @test */
    public function it_removes_object_rules()
    {
        $rules = ['required', new Unique('users', 'email'), 'max:255'];
        $result = remove_rule($rules, ['unique']);
        
        $this->assertCount(2, $result);
        $this->assertEquals('required', $result[0]);
        $this->assertEquals('max:255', $result[1]);
    }

    /** @test */
    public function it_returns_empty_string_when_all_rules_removed_from_string()
    {
        $rules = 'required|email';
        $result = remove_rule($rules, ['required', 'email']);
        
        $this->assertEquals('', $result);
    }

    /** @test */
    public function it_returns_empty_array_when_all_rules_removed_from_array()
    {
        $rules = ['required', 'email'];
        $result = remove_rule($rules, ['required', 'email']);
        
        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_returns_original_string_when_no_matching_rules()
    {
        $rules = 'required|email|max:255';
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertEquals('required|email|max:255', $result);
    }

    /** @test */
    public function it_returns_original_array_when_no_matching_rules()
    {
        $rules = ['required', 'email', 'max:255'];
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertEquals(['required', 'email', 'max:255'], $result);
    }

    /** @test */
    public function it_handles_empty_rules_to_remove()
    {
        $rules = 'required|email|max:255';
        $result = remove_rule($rules, []);
        
        $this->assertEquals('required|email|max:255', $result);
    }

    /** @test */
    public function it_handles_empty_rules_string()
    {
        $rules = '';
        $result = remove_rule($rules, ['required']);
        
        $this->assertEquals('', $result);
    }

    /** @test */
    public function it_handles_empty_rules_array()
    {
        $rules = [];
        $result = remove_rule($rules, ['required']);
        
        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_removes_rules_case_insensitively_matching_start()
    {
        $rules = 'required|email|max:255';
        $result = remove_rule($rules, ['max']);
        
        $this->assertEquals('required|email', $result);
    }

    /** @test */
    public function it_preserves_order_of_remaining_rules()
    {
        $rules = ['required', 'email', 'unique:users', 'max:255', 'min:3', 'confirmed'];
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertEquals(['required', 'email', 'max:255', 'confirmed'], $result);
    }

    /** @test */
    public function it_removes_nullable_rule()
    {
        $rules = 'nullable|sometimes|email';
        $result = remove_rule($rules, ['nullable']);
        
        $this->assertEquals('sometimes|email', $result);
    }

    /** @test */
    public function it_removes_sometimes_rule()
    {
        $rules = ['sometimes', 'required', 'email'];
        $result = remove_rule($rules, ['sometimes']);
        
        $this->assertEquals(['required', 'email'], $result);
    }

    /** @test */
    public function it_handles_complex_validation_rules()
    {
        $rules = 'required|regex:/^[a-z]+$/|unique:users,username,1,id|between:3,20';
        $result = remove_rule($rules, ['unique', 'regex']);
        
        $this->assertEquals('required|between:3,20', $result);
    }

    /** @test */
    public function it_removes_in_rule_with_multiple_values()
    {
        $rules = 'required|in:admin,user,moderator|email';
        $result = remove_rule($rules, ['in']);
        
        $this->assertEquals('required|email', $result);
    }

    /** @test */
    public function it_maintains_array_index_integrity()
    {
        $rules = ['required', 'email', 'unique:users'];
        $result = remove_rule($rules, ['email']);
        
        // Should return properly re-indexed array [0, 1] not [0, 2]
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayNotHasKey(2, $result);
    }

    /** @test */
    public function it_handles_mixed_rule_formats_in_array()
    {
        $rules = ['required', 'email', new Unique('users'), 'max:255', 'min:3'];
        $result = remove_rule($rules, ['unique', 'min']);
        
        $this->assertCount(3, $result);
        $this->assertEquals(['required', 'email', 'max:255'], $result);
    }
}
