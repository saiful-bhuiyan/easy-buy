<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Electronics',
                'slug' => 'electronics',
                'meta_title' => 'Electronics',
                'meta_description' => 'Electronics',
                'meta_keyword' => 'Electronics',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Clothing',
                'slug' => 'clothing',
                'meta_title' => 'Clothing',
                'meta_description' => 'Clothing',
                'meta_keyword' => 'Clothing',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Furniture',
                'slug' => 'furniture',
                'meta_title' => 'Furniture',
                'meta_description' => 'Furniture',
                'meta_keyword' => 'Furniture',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Books',
                'slug' => 'books',
                'meta_title' => 'Books',
                'meta_description' => 'Books',
                'meta_keyword' => 'Books',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Sports',
                'slug' => 'sports',
                'meta_title' => 'Sports',
                'meta_description' => 'Sports',
                'meta_keyword' => 'Sports',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Home & Garden',
                'slug' => 'home-garden',
                'meta_title' => 'Home & Garden',
                'meta_description' => 'Home & Garden',
                'meta_keyword' => 'Home & Garden',
                'status' => 1,
                'user_id' => 1
            ],
        ];

        Category::insert($categories);


        $electronicsSubCategories = [
            [
                'title' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'meta_title' => 'Mobiles',
                'meta_description' => 'Mobile Phones',
                'meta_keyword' => 'Mobiles',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Laptops',
                'slug' => 'laptops',
                'meta_title' => 'Laptops',
                'meta_description' => 'Laptop Computers',
                'meta_keyword' => 'Laptops',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Smartwatches',
                'slug' => 'smartwatches',
                'meta_title' => 'Smartwatches',
                'meta_description' => 'Explore the latest smartwatches with advanced features.',
                'meta_keyword' => 'smartwatches, wearable devices',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Cameras',
                'slug' => 'cameras',
                'meta_title' => 'Cameras',
                'meta_description' => 'Capture memories with high-quality digital cameras.',
                'meta_keyword' => 'cameras, digital cameras, DSLRs',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Headphones',
                'slug' => 'headphones',
                'meta_title' => 'Headphones',
                'meta_description' => 'Immerse yourself in music with premium headphones.',
                'meta_keyword' => 'headphones, earphones, audio devices',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Tablets',
                'slug' => 'tablets',
                'meta_title' => 'Tablets',
                'meta_description' => 'Discover the latest tablets with powerful features.',
                'meta_keyword' => 'tablets, tablets for sale, tablet devices',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Gaming Consoles',
                'slug' => 'gaming-consoles',
                'meta_title' => 'Gaming Consoles',
                'meta_description' => 'Experience the thrill of gaming with the latest consoles.',
                'meta_keyword' => 'gaming consoles, video game consoles, game systems',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Drones',
                'slug' => 'drones',
                'meta_title' => 'Drones',
                'meta_description' => 'Explore the skies with advanced drone technology.',
                'meta_keyword' => 'drones, UAVs, quadcopters',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Home Appliances',
                'slug' => 'home-appliances',
                'meta_title' => 'Home Appliances',
                'meta_description' => 'Make your home smarter with innovative appliances.',
                'meta_keyword' => 'home appliances, smart home devices, kitchen appliances',
                'status' => 1,
                'user_id' => 1
            ],
        ];

        $clothingSubCategories = [
            [
                'title' => 'T-shirts',
                'slug' => 't-shirts',
                'meta_title' => 'T-shirts',
                'meta_description' => 'Casual T-shirts',
                'meta_keyword' => 'T-shirts',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Jeans',
                'slug' => 'jeans',
                'meta_title' => 'Jeans',
                'meta_description' => 'Denim Jeans',
                'meta_keyword' => 'Jeans',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Dresses',
                'slug' => 'dresses',
                'meta_title' => 'Dresses',
                'meta_description' => 'Explore a wide range of fashionable dresses for all occasions.',
                'meta_keyword' => 'dresses, party dresses, casual dresses',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Sweaters',
                'slug' => 'sweaters',
                'meta_title' => 'Sweaters',
                'meta_description' => 'Stay warm and stylish with our collection of cozy sweaters.',
                'meta_keyword' => 'sweaters, knitwear, pullovers',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Jackets',
                'slug' => 'jackets',
                'meta_title' => 'Jackets',
                'meta_description' => 'Discover trendy jackets to complement your outfit.',
                'meta_keyword' => 'jackets, outerwear, coats',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Skirts',
                'slug' => 'skirts',
                'meta_title' => 'Skirts',
                'meta_description' => 'Find the perfect skirt to add flair to your wardrobe.',
                'meta_keyword' => 'skirts, mini skirts, maxi skirts',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Suits',
                'slug' => 'suits',
                'meta_title' => 'Suits',
                'meta_description' => 'Elevate your style with our collection of sophisticated suits.',
                'meta_keyword' => 'suits, formal wear, business attire',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Activewear',
                'slug' => 'activewear',
                'meta_title' => 'Activewear',
                'meta_description' => 'Stay comfortable and stylish during your workouts with our activewear collection.',
                'meta_keyword' => 'activewear, gym clothes, sportswear',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Outerwear',
                'slug' => 'outerwear',
                'meta_title' => 'Outerwear',
                'meta_description' => 'Stay cozy and fashionable with our range of outerwear options.',
                'meta_keyword' => 'outerwear, winter jackets, parkas',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Undergarments',
                'slug' => 'undergarments',
                'meta_title' => 'Undergarments',
                'meta_description' => 'Discover comfortable and supportive undergarments for everyday wear.',
                'meta_keyword' => 'undergarments, lingerie, underwear',
                'status' => 1,
                'user_id' => 1
            ],
        ];

        $furnitureSubCategories = [
            [
                'title' => 'Sofas',
                'slug' => 'sofas',
                'meta_title' => 'Sofas',
                'meta_description' => 'Living Room Sofas',
                'meta_keyword' => 'Sofas',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Beds',
                'slug' => 'beds',
                'meta_title' => 'Beds',
                'meta_description' => 'Bedroom Beds',
                'meta_keyword' => 'Beds',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Dining Tables',
                'slug' => 'dining-tables',
                'meta_title' => 'Dining Tables',
                'meta_description' => 'Elegant dining tables for your home.',
                'meta_keyword' => 'dining tables, dining room furniture',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Office Desks',
                'slug' => 'office-desks',
                'meta_title' => 'Office Desks',
                'meta_description' => 'Functional and stylish desks for your office space.',
                'meta_keyword' => 'office desks, workstations, office furniture',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Bookcases',
                'slug' => 'bookcases',
                'meta_title' => 'Bookcases',
                'meta_description' => 'Organize your books with our range of bookcases.',
                'meta_keyword' => 'bookcases, shelving units, storage furniture',
                'status' => 1,
                'user_id' => 1
            ],
            [
                'title' => 'Coffee Tables',
                'slug' => 'coffee-tables',
                'meta_title' => 'Coffee Tables',
                'meta_description' => 'Add style to your living room with our selection of coffee tables.',
                'meta_keyword' => 'coffee tables, living room furniture',
                'status' => 1,
                'user_id' => 1
            ],
        ];

        // Add more subcategories for other categories if needed

        $this->insertSubCategories($electronicsSubCategories, 'Electronics');
        $this->insertSubCategories($clothingSubCategories, 'Clothing');
        $this->insertSubCategories($furnitureSubCategories, 'Furniture');
    }

    private function insertSubCategories(array $subCategories, string $categoryTitle)
    {
        $categoryId = Category::where('title', $categoryTitle)->value('id');
        $data = [];

        foreach ($subCategories as $subCategory) {
            $data[] = array_merge($subCategory, ['category_id' => $categoryId]);
        }

        SubCategory::insert($data);
    
    }
}
