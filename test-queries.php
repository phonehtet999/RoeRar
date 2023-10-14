<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
]);

$user->customer()->create([
    'phone_number' => '123456',
    'address' => 'Yangon',
]);

$user->staff()->create([
    'position' => 'Junior Casher',
    'phone_number' => '123456',
    'salary' => 50000,
    'address' => 'Mandalay',
]);

$user->supplier()->create([
    'company_name' => 'U Gyan',
    'phone_number' => '123456',
    'address' => 'Mandalay',
]);

$supplier = Supplier::first();

$supplier->brands()->create([
    'name' => 'U Gyan',
    'description' => 'U Gyan Distribution',
]);

$category = Category::create([
    'name' => 'Pone San',
    'design' => 'Testing',
    'description' => 'Testing',
]);

$product = Product::create([
    'name' => 'U Gyan Product 1',
    'category_id' => 1,
    'brand_id' => 1,
    'color' => '#000000',
    'unit_selling_price' => 10000,
    'unit_buying_price' => 9000,
    'quantity' => 100,
    'minimum_required_quantity' => 10,
    'status' => 'in_stock',
]);