<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'Administrator', 'slug' => 'admin', 'permissions' => ['manage_users', 'manage_inventory', 'manage_products', 'manage_services', 'view_reports', 'process_sales', 'manage_settings', 'manage_owners']]);
        Role::create(['name' => 'Manager', 'slug' => 'manager', 'permissions' => ['manage_inventory', 'manage_products', 'manage_services', 'view_reports', 'process_sales', 'manage_owners']]);
        $cashier = Role::create(['name' => 'Cashier', 'slug' => 'cashier', 'permissions' => ['process_sales', 'manage_owners']]);
        Role::create(['name' => 'Veterinary Staff', 'slug' => 'vet_staff', 'permissions' => ['manage_owners', 'process_sales']]);

        User::create(['name' => 'Admin', 'email' => 'admin@vetpos.com', 'password' => Hash::make('password'), 'role_id' => $admin->id]);
        User::create(['name' => 'Maria Santos', 'email' => 'maria@vetpos.com', 'password' => Hash::make('password'), 'role_id' => $cashier->id]);
        User::create(['name' => 'Juan Dela Cruz', 'email' => 'juan@vetpos.com', 'password' => Hash::make('password'), 'role_id' => $cashier->id]);

        $foodCat = ProductCategory::create(['name' => 'Pet Food', 'slug' => 'pet-food']);
        $vitCat = ProductCategory::create(['name' => 'Vitamins & Supplements', 'slug' => 'vitamins']);
        $medCat = ProductCategory::create(['name' => 'Medicines', 'slug' => 'medicines']);
        ProductCategory::create(['name' => 'Flea & Tick Products', 'slug' => 'flea-tick']);
        ProductCategory::create(['name' => 'Grooming Products', 'slug' => 'grooming-products']);
        $accCat = ProductCategory::create(['name' => 'Accessories', 'slug' => 'accessories']);

        Product::create(['sku' => 'PF-001', 'barcode' => '8901234567890', 'name' => 'Royal Canin Adult Dog 2kg', 'product_category_id' => $foodCat->id, 'brand' => 'Royal Canin', 'unit' => 'bag', 'cost_price' => 850, 'selling_price' => 1200, 'stock' => 25, 'reorder_level' => 5]);
        Product::create(['sku' => 'PF-002', 'barcode' => '8901234567891', 'name' => 'Whiskas Cat Food 1.5kg', 'product_category_id' => $foodCat->id, 'brand' => 'Whiskas', 'unit' => 'bag', 'cost_price' => 350, 'selling_price' => 520, 'stock' => 30, 'reorder_level' => 5]);
        Product::create(['sku' => 'PF-003', 'name' => 'Pedigree Puppy Dog Food 3kg', 'product_category_id' => $foodCat->id, 'brand' => 'Pedigree', 'unit' => 'bag', 'cost_price' => 400, 'selling_price' => 580, 'stock' => 15, 'reorder_level' => 5]);
        Product::create(['sku' => 'PF-004', 'name' => 'Friskies Indoor Cat 1.2kg', 'product_category_id' => $foodCat->id, 'brand' => 'Friskies', 'unit' => 'bag', 'cost_price' => 200, 'selling_price' => 310, 'stock' => 20, 'reorder_level' => 5]);
        Product::create(['sku' => 'VT-001', 'barcode' => '8901234567892', 'name' => 'Pet Vit Plus Syrup 120ml', 'product_category_id' => $vitCat->id, 'brand' => 'PetVet', 'unit' => 'bottle', 'cost_price' => 180, 'selling_price' => 280, 'stock' => 18, 'reorder_level' => 5]);
        Product::create(['sku' => 'VT-002', 'name' => 'Calcium Bone Tabs (60 tabs)', 'product_category_id' => $vitCat->id, 'brand' => 'PetVet', 'unit' => 'box', 'cost_price' => 150, 'selling_price' => 230, 'stock' => 12, 'reorder_level' => 5]);
        Product::create(['sku' => 'MD-001', 'barcode' => '8901234567893', 'name' => 'Amoxicillin 250mg (20 caps)', 'product_category_id' => $medCat->id, 'brand' => 'Generic', 'unit' => 'box', 'cost_price' => 120, 'selling_price' => 200, 'stock' => 2, 'reorder_level' => 5]);
        Product::create(['sku' => 'MD-002', 'name' => 'Deworming Tablets (4 tabs)', 'product_category_id' => $medCat->id, 'brand' => 'Generic', 'unit' => 'pack', 'cost_price' => 80, 'selling_price' => 150, 'stock' => 8, 'reorder_level' => 5]);
        Product::create(['sku' => 'FT-001', 'barcode' => '8901234567894', 'name' => 'Frontline Plus Dog 3x Pipette', 'product_category_id' => ProductCategory::where('slug', 'flea-tick')->first()->id, 'brand' => 'Frontline', 'unit' => 'box', 'cost_price' => 600, 'selling_price' => 850, 'stock' => 10, 'reorder_level' => 3]);
        Product::create(['sku' => 'GM-001', 'name' => 'Oatmeal Shampoo 500ml', 'product_category_id' => ProductCategory::where('slug', 'grooming-products')->first()->id, 'brand' => 'Bio-Groom', 'unit' => 'bottle', 'cost_price' => 220, 'selling_price' => 350, 'stock' => 14, 'reorder_level' => 5]);
        Product::create(['sku' => 'AC-001', 'name' => 'Adjustable Dog Collar (M)', 'product_category_id' => $accCat->id, 'brand' => 'Generic', 'unit' => 'pcs', 'cost_price' => 80, 'selling_price' => 150, 'stock' => 20, 'reorder_level' => 5]);
        Product::create(['sku' => 'AC-002', 'name' => 'Retractable Dog Leash', 'product_category_id' => $accCat->id, 'brand' => 'Generic', 'unit' => 'pcs', 'cost_price' => 200, 'selling_price' => 380, 'stock' => 1, 'reorder_level' => 5]);

        $vetCat = ServiceCategory::create(['name' => 'Consultation', 'slug' => 'consultation']);
        $vaxCat = ServiceCategory::create(['name' => 'Vaccination', 'slug' => 'vaccination']);
        $procCat = ServiceCategory::create(['name' => 'Procedures', 'slug' => 'procedures']);
        ServiceCategory::create(['name' => 'Grooming', 'slug' => 'grooming']);

        Service::create(['name' => 'Veterinary Consultation', 'service_category_id' => $vetCat->id, 'description' => 'General vet consultation and examination', 'price' => 300, 'duration_minutes' => 30]);
        Service::create(['name' => 'General Check-up', 'service_category_id' => $vetCat->id, 'price' => 500, 'duration_minutes' => 45]);
        Service::create(['name' => 'Anti-Rabies Vaccination', 'service_category_id' => $vaxCat->id, 'description' => 'Annual anti-rabies vaccine', 'price' => 600, 'duration_minutes' => 15]);
        Service::create(['name' => '5-in-1 Vaccination (DHPPL)', 'service_category_id' => $vaxCat->id, 'price' => 800, 'duration_minutes' => 15]);
        Service::create(['name' => 'FVRCP Vaccination (Cat)', 'service_category_id' => $vaxCat->id, 'price' => 750, 'duration_minutes' => 15]);
        Service::create(['name' => 'Deworming Treatment', 'service_category_id' => $procCat->id, 'price' => 250, 'duration_minutes' => 15]);
        Service::create(['name' => 'Spay (Female)', 'service_category_id' => $procCat->id, 'price' => 3500, 'duration_minutes' => 120]);
        Service::create(['name' => 'Neuter (Male)', 'service_category_id' => $procCat->id, 'price' => 2500, 'duration_minutes' => 90]);
        Service::create(['name' => 'Nail Trimming', 'service_category_id' => ServiceCategory::where('slug', 'grooming')->first()->id, 'price' => 100, 'duration_minutes' => 10]);
        Service::create(['name' => 'Full Grooming', 'service_category_id' => ServiceCategory::where('slug', 'grooming')->first()->id, 'description' => 'Bath, haircut, nail trim, ear cleaning', 'price' => 500, 'duration_minutes' => 60]);

        $owner1 = Owner::create(['full_name' => 'Maria Garcia', 'contact_number' => '09171234567', 'email' => 'maria.garcia@email.com', 'address' => '123 Rizal Ave, Tacloban City']);
        $owner2 = Owner::create(['full_name' => 'Pedro Reyes', 'contact_number' => '09181234567', 'email' => 'pedro.reyes@email.com', 'address' => '456 Magsaysay Blvd, Tacloban City']);
        $owner3 = Owner::create(['full_name' => 'Ana Torres', 'contact_number' => '09191234567', 'address' => '789 Romualdez St, Tacloban City']);
        Owner::create(['full_name' => 'Luis Mendoza', 'contact_number' => '09201234567', 'address' => '321 San Jose, Tacloban City']);
        Owner::create(['full_name' => 'Sofia Lim', 'contact_number' => '09211234567', 'email' => 'sofia.lim@email.com', 'address' => '654 Magallanes St, Tacloban City']);

        Pet::create(['owner_id' => $owner1->id, 'name' => 'Brownie', 'species' => 'Dog', 'breed' => 'Aspin', 'sex' => 'male', 'color' => 'Brown', 'weight' => 12.5]);
        Pet::create(['owner_id' => $owner1->id, 'name' => 'Whiskers', 'species' => 'Cat', 'breed' => 'Persian', 'sex' => 'female', 'color' => 'White', 'weight' => 4.2]);
        Pet::create(['owner_id' => $owner2->id, 'name' => 'Max', 'species' => 'Dog', 'breed' => 'German Shepherd', 'sex' => 'male', 'color' => 'Black & Tan', 'weight' => 30.0]);
        Pet::create(['owner_id' => $owner3->id, 'name' => 'Mimi', 'species' => 'Cat', 'breed' => 'Siamese', 'sex' => 'female', 'color' => 'Cream', 'weight' => 3.8]);
        Pet::create(['owner_id' => $owner3->id, 'name' => 'Buddy', 'species' => 'Dog', 'breed' => 'Shih Tzu', 'sex' => 'male', 'color' => 'White & Brown', 'weight' => 6.0]);

        Setting::set('business_name', 'VetPOS Animal Clinic & Pet Shop');
        Setting::set('business_address', '123 Roxas Ave, Tacloban City, Leyte');
        Setting::set('business_phone', '(053) 321-4567');
        Setting::set('business_email', 'info@vetpos.com');
        Setting::set('currency', '₱');
        Setting::set('tax_rate', '0');
        Setting::set('receipt_footer', 'Thank you for your visit! Your pets deserve the best care.');
        Setting::set('low_stock_threshold', '5');
    }
}
