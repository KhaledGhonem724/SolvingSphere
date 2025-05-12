# Laravel Core Structure Summary
## Laravel : MVC Structure

### **Controllers** (`solvingsphere/app/Http/Controllers/`)
* Handle **logic** for user requests.
* Receive data from routes, interact with models, and return views or JSON.
```php
// Example
public function index() {
    $posts = Post::all();
    return view('posts.index', compact('posts'));
}
```
---

### **Models** (`solvingsphere/app/Models/`)
* Represent a **database table** using Eloquent ORM.
* Define relationships and data logic.
```php
class Post extends Model {
    protected $fillable = ['title', 'body'];
}
```
---

### **Views** (`solvingsphere/resources/views/`)
* Blade templates for the **user interface**.
* Use logic + HTML to display data from controllers.
```blade
<!-- posts/index.blade.php -->
@foreach ($posts as $post)
    <h2>{{ $post->title }}</h2>
@endforeach
```
---

## Laravel : Database Component
### **Factories** (`solvingsphere/database/factories/`)
* Used to **generate fake data** for testing or seeding.
* Often paired with models and seeders.
```php
Post::factory()->count(10)->create();
```
---

### **Migrations** (`solvingsphere/database/migrations/`)
* Define **database structure** in PHP.
* Track changes to the schema over time (versioning).
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->timestamps();
});
```
---

### **Seeders** (`solvingsphere/database/seeders/`)
* Insert **sample or default data** into your tables.
* Often used to fill the DB with test data after migrations.
```php
Post::factory(10)->create();
```
---



# 🛠️ Stages of Building a Laravel App

### Stage 1: **Set Up Project**
* Install Laravel via Composer.
* Configure `.env` for DB and app settings.
```bash
composer create-project laravel/laravel my-app
```
---

### 🧱 Stage 2: **Design Database**
* Plan your tables and relationships.
* Create **models and migrations**.
```bash
php artisan make:model Post -m
```
---

### 📂 Stage 3: **Run Migrations**
* Build your database schema using migrations.
```bash
php artisan migrate
```
---

### 🏭 Stage 4: **Create Factories & Seeders**
* Add test data with factories and seeders.
```bash
php artisan make:seeder PostSeeder
php artisan db:seed
```
---

### ⚙️ Stage 5: **Define Routes and Controllers**
* Add logic to respond to HTTP requests.
* Create **controllers** and connect them to **routes**.
```bash
php artisan make:controller PostController
```
---

### 🧠 Stage 6: **Build Views**
* Create **Blade templates** for displaying content.
* Pass data from controller to view.
---

### 🌐 Stage 7: **Test and Run App**
* Use Laravel’s built-in server to run the app.
```bash
php artisan serve
```
* Test routes, views, and data flow.
---
