

# Laravel Core Structure Summary
```
SolvingSphere/
│
├── app/
│   ├── Http/Controllers/       ← 🟢 Controllers (C)
│   │   ├── Problems/           ← 🟢 Folder for problems section contains multibles files or folders if needed
│   │   │   ├── ProblemController.php
│   │   │   ├── ...
│   │   │   └── any other files you need
│   │   ├── Blogs/
│   │   │   └── ...
│   │   ├── Containers/
│   │   │   └── ...
│   │   ├── Status/
│   │   │   └── ...
│   │   ├── Groups/
│   │   │   └── ...
│   │   ├── Admins/
│   │   │   └── ...
│   │   └── Profiles/
│   │       └── ...
│   │
│   │
│   └── Models/                 ← 🔵 Models (M)
│       ├── problems.php        ← 🔵 file for problems sectoin (usually contains one Model)
│       ├── blogs.php
│       ├── containers.php
│       ├── status.php
│       ├── groups.php
│       ├── admin.php
│       └── profile.php
│
│
├── database/                   ← 🟠 Folder to control the database
│   ├── factories/              ← 🟠 Factories (Generate fake or static data according to Models)
│   │   └── ProblemFactory.php
│   ├── migrations/             ← 🟠 Migrations (define schema for Models)
│   │   └── 2024_01_01_create_problems_table.php
│   └── seeders/                ← 🟠 Seeders (Fill the tables "Models" with factories' data)
│       └── ProblemSeeder.php
│
│
├── resources/
│   ├── views/                  ← 🔴 ALL Views (V)
│   │   ├── Components/         ← 🔴 Blade General Components 
│   │   │   └── MyButton.blade.php
│   │   ├── index.blade.php
│   │   ├── Problems/           ← 🔴 Views for problems Section
│   │   │   ├── all_problems.blade.php
│   │   │   ├── ...
│   │   │   └── problem.blade.php
│   │   ├── Blogs/
│   │   │   └── ...
│   │   ├── Containers/
│   │   │   └── ...
│   │   ├── Status/
│   │   │   └── ...
│   │   ├── Groups/
│   │   │   └── ...
│   │   ├── Admins/
│   │   │   └── ...
│   │   └── Profiles/
│   │       └── ...
│   ├── js/                     ← 🔴 React General files
│   │   ├── Components/         ← 🔴 React General Components 
│   │   │   └── MyButton.jsx
│   │   └── app.jsx
│   └── css/                    ← 🔴 CSS General files (for custom styles)
│       └── app.css         
├── routes/
│   └── web.php                ← Connects routes to Controllers
```

### 🗂️ Legend:
* 🟢 **Controllers**: Handle app logic and return views or JSON.
* 🔵 **Models**: Represent data and DB logic using Eloquent ORM.
* 🔴 **Views**: Blade templates for rendering HTML.
* 🟠 **Databases**: Manage database
---

# Laravel: MVC Structure

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

# Laravel : Database Component


### **Migrations** (`solvingsphere/database/migrations/`)
* Define **database structure** in PHP.
* Track changes to the schema over time (versioning).
```php
Schema::create('problems', function (Blueprint $table) {
    $table->string('problem_handle')->primary();
    $table->string('website');
    $table->string('title');
    .
    ...
    $table->timestamps();
});
```
---
### **Factories** (`solvingsphere/database/factories/`)
* Used to **generate fake data** for testing or seeding.
* Often paired with models and seeders.
```php
Problem::factory()->count(10)->create();
```
---
### **Seeders** (`solvingsphere/database/seeders/`)
* Insert **sample or default data** into your tables.
* Often used to fill the DB with test data after migrations.
```php
Problem::factory(10)->create();
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

