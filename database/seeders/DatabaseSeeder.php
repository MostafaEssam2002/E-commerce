<?php
namespace Database\Seeders;
use App\Models\categories;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
        ["name" => 'electronics', "name_ar" => 'إلكترونيات', "description" => " ", "image_path" => 'assets/img/products/electronics.jpg'],
        ["name" => 'watches', "name_ar" => 'ساعات', "description" => " ", "image_path" => 'assets/img/products/watches.jpg'],
        ["name" => 'bags', "name_ar" => 'حقائب', "description" => " ", "image_path" => 'assets/img/products/bags.jpg'],
        ["name" => 'makeup', "name_ar" => 'مكياج', "description" => " ", "image_path" => 'assets/img/products/makeup.jpg'],
        ["name" => 'cameras', "name_ar" => 'كاميرات', "description" => " ", "image_path" => 'assets/img/products/cameras.jpg'],
        ["name" => 'food', "name_ar" => 'طعام', "description" => " ", "image_path" => 'assets/img/products/food.jpg'],
        ["name" => 'furniture', "name_ar" => 'أثاث', "description" => " ", "image_path" => 'assets/img/products/furniture.jpg'],  // فئة جديدة
        ["name" => 'clothing', "name_ar" => 'ملابس', "description" => " ", "image_path" => 'assets/img/products/clothing.jpg'],  // فئة جديدة
        ["name" => 'shoes', "name_ar" => 'أحذية', "description" => " ", "image_path" => 'assets/img/products/shoes.jpg'],  // فئة جديدة
        ["name" => 'sports', "name_ar" => 'رياضة', "description" => " ", "image_path" => 'assets/img/products/sports.jpg'],  // فئة جديدة
        ["name" => 'toys', "name_ar" => 'ألعاب', "description" => " ", "image_path" => 'assets/img/products/toys.jpg'],  // فئة جديدة
        ["name" => 'health', "name_ar" => 'صحة', "description" => " ", "image_path" => 'assets/img/products/health.jpg']   // فئة جديدة
    ];

        $products = [
            ["seller_id"=>1,"id" => 1, "shipping"=>15,"name" => 'Green apples have polyphenols','name_ar'=> 'التفاح الأخضر يحتوي على البوليفينول', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 50, "image_path" => 'assets/img/products/product-img-5.jpg', "quantity" => 50, "category_id" => 6],
            ["seller_id"=>1,"id" => 2, "shipping"=>43,"name_ar"=> 'كاميرا كانون 14',"name" => 'Camera canon 14', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 5000, "image_path" => 'assets/img/products/camera canon.jpg', "quantity" => 5, "category_id" => 5],
            ["seller_id"=>1,"id" => 3, "shipping"=>100,"name_ar"=> 'لابتوب',"name" => 'Laptop', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 80000, "image_path" => 'assets/img/products/laptop.jpg', "quantity" => 2, "category_id" => 1],
            ["seller_id"=>1,"id" => 4, "shipping"=>12,"name_ar"=> 'حذاء جري نايك',"name" => 'Nike Running Shoes', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 1200, "image_path" => 'assets/img/products/nike_shoes.jpg', "quantity" => 10, "category_id" => 7],
            ["seller_id"=>1,"id" => 5, "shipping"=>14,"name_ar"=> 'ساعة ذكية السلسلة 6',"name" => 'Smart Watch Series 6', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 3500, "image_path" => 'assets/img/products/smart_watch.jpg', "quantity" => 8, "category_id" => 2],
            ["seller_id"=>1,"id" => 6, "shipping"=>0,"name_ar"=> 'حقيبة يد جلد',"name" => 'Leather Handbag', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 1500, "image_path" => 'assets/img/products/leather_bag.jpg', "quantity" => 20, "category_id" => 3],
            ["seller_id"=>1,"id" => 7, "shipping"=>33,"name_ar"=> 'سماعة بلوتوث',"name" => 'Bluetooth Speaker', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 800, "image_path" => 'assets/img/products/bluetooth_speaker.jpg', "quantity" => 15, "category_id" => 1],
            ["seller_id"=>1,"id" => 8, "shipping"=>10,"name_ar"=> 'كرسي مكتب',"name" => 'Office Chair', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 2000, "image_path" => 'assets/img/products/office_chair.jpg', "quantity" => 10, "category_id" => 7],
            ["seller_id"=>1,"id" => 9, "shipping"=>145,"name_ar"=> 'تي شيرت رجالي',"name" => 'Men T-Shirt', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 400, "image_path" => 'assets/img/products/men_tshirt.jpg', "quantity" => 30, "category_id" => 8],
            ["seller_id"=>1,"id" => 10,"shipping"=>245,"name_ar"=> 'سيارة لعبة',"name" => 'Toy Car', "description" => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.', "price" => 250, "image_path" => 'assets/img/products/toy_car.jpg', "quantity" => 100, "category_id" => 10]
        ];

        $reviews=[
            ["id"=>1,"name"=>"David Niph","email"=>"DavidNiph@gmail.com","phone"=>"01148090115","subject"=>"Local shop owner","message"=>"Sed ut perspiciatis unde omnis iste natus error veritatis et  quasi architecto beatae vitae dict eaque ipsa quae ab illo inventore Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium","image_path"=>"assets/img/avaters/avatar1.png"],
            ["id"=>2,"name"=>"Saira Hakim","email"=>"SairaHakim@gmail.com","phone"=>"01148090115","subject"=>"Local shop owner","message"=>"Sed ut perspiciatis unde omnis iste natus error veritatis et  quasi architecto beatae vitae dict eaque ipsa quae ab illo inventore Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium","image_path"=>"assets/img/avaters/avatar2.png"],
            ["id"=>3,"name"=>"Jacob Sikim","email"=>"JacobSikim@gmail.com","phone"=>"01148090115","subject"=>"Local shop owner","message"=>"Sed ut perspiciatis unde omnis iste natus error veritatis et  quasi architecto beatae vitae dict eaque ipsa quae ab illo inventore Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium","image_path"=>"assets/img/avaters/avatar3.png"],
        ];
        $users = [
            ["id"=>1,"name"=>"admin","email"=>"admin@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>"$2y$12$49S7qGyno0i2sNyyoPSZ/ODzp4RkMSOshdKqzEq1VBBu4pnhZFqJe","remember_token"=>"9166ozUCyuCTQ6sxOEi6HofZO6jar1ge8bvUh7VRFNg2xM44Rkaoz8NABHOw","created_at"=>"2025-09-24 02:49:00","updated_at"=>"2025-09-24 02:49:00","role"=>"admin"],
            ["id"=>2,"name"=>"ahmed","email"=>"ahmed@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$dxo26kbUX269QzMpwl4FBOdtx0Q.OKU96Q4kQ45t0.zR8.ysEuzf6',"remember_token"=>"hTUvV0twguWfZyJx4JwaxF8KrSQ2WTMPbZBVj76tGaE2tLvOfuvkSfnDS1bJ","created_at"=>"2025-09-24 04:06:15","updated_at"=>"2025-09-24 04:06:15","role"=>"user"],
            ["id"=>3,"name"=>"amr","email"=>"amr@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$nY0MEAlPPF/g2a4L.5/ZkOjN6UtRntIPoSm7uk3GDQSi9uPciP6iS',"remember_token"=>null,"created_at"=>"2025-09-26 09:34:30","updated_at"=>"2025-09-26 09:34:30","role"=>"user"],
            ["id"=>7,"name"=>"mostafa essam","email"=>"mostafa@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$l/eQwhbnR10J6k5o3LlJ.utaOz5DIBeUoLkzTD2Z9aTtCHQ7dRfRC',"remember_token"=>null,"created_at"=>"2025-09-26 13:52:35","updated_at"=>"2025-09-26 13:52:35","role"=>"user"],
            ["id"=>8,"name"=>"test","email"=>"test@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$L.ppgd6N6WWoeLsbxLzBdeJhBoNgrUCNl/vGRhTtCcBdTuy0ky3bS',"remember_token"=>null,"created_at"=>"2025-09-26 14:11:28","updated_at"=>"2025-09-26 14:11:28","role"=>"user"],
            ["id"=>9,"name"=>"seller","email"=>"seller@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$xeP1Qk11u2emiaibGMopo.Dbl7C0WXHdeNVA9ypdWilkA0PsXgdfS',"remember_token"=>null,"created_at"=>"2025-09-27 01:00:04","updated_at"=>"2025-09-27 01:00:04","role"=>"seller"],
            ["id"=>10,"name"=>"tester","email"=>"tester@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$nv12rBqxKXY4qHZJXo4EwObZFOOyEfVO2onlrnXQvIcwuUyOBydka',"remember_token"=>null,"created_at"=>"2025-09-27 01:07:40","updated_at"=>"2025-09-27 01:07:40","role"=>"user"],
            ["id"=>11,"name"=>"mostafa essam","email"=>"mo@gmail.com","avatar"=>"assets/img/users/d7b7bc28-0734-4d2a-9e6a-f4ffbf8c06db.jpg","email_verified_at"=>null,"password"=>'$2y$12$i6tVt7aK959vOUDjoTC5z.0GoweuOHelEQ4YNdDd6aRXunDVfQJTC',"remember_token"=>null,"created_at"=>"2025-09-27 02:27:56","updated_at"=>"2025-09-27 02:27:56","role"=>"user"],
        ];
        User::insert($users);

        categories::insert($categories);
        Product::insert($products);
        Review::insert($reviews);
    }
}
// 12
