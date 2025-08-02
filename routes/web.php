<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\BlogCategoryController;
use App\Http\Controllers\Home\BlogController;
use App\Http\Controllers\Home\ContactController;
use App\Http\Controllers\Home\FooterController;
use App\Http\Controllers\Home\HomeSliderController;
use App\Http\Controllers\Home\PortfolioController;
// use App\Http\Controllers\Home\HomeSliderController;
// use App\Http\Controllers\Home\AboutController;
// use App\Http\Controllers\Home\PortfolioController;
// use App\Http\Controllers\Home\BlogCategoryController;
// use App\Http\Controllers\Home\BlogController;
// use App\Http\Controllers\Home\FooterController;
// use App\Http\Controllers\Home\ContactController;
use App\Http\Controllers\Pos\EmployeeController;
use App\Http\Controllers\Pos\EmployeeAttendanceController;

use App\Http\Controllers\Pos\SupplierController;
use App\Http\Controllers\Pos\CustomerController;
use App\Http\Controllers\Pos\UnitController;
use App\Http\Controllers\Pos\CategoryController;
use App\Http\Controllers\Pos\ProductController;
use App\Http\Controllers\Pos\PurchaseController;
use App\Http\Controllers\Pos\DefaultController;
use App\Http\Controllers\Pos\ExpenseController;
use App\Http\Controllers\Pos\InvoiceController;
use App\Http\Controllers\Pos\PartialPaymentController;
use App\Http\Controllers\Pos\SalaryController;
use App\Http\Controllers\Pos\StockController;
use App\Models\Employee;
use App\Models\Expense;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
})->name('home');


Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/invoice/autocomplete', [InvoiceController::class, 'autocomplete'])->name('invoice.autocomplete');
Route::get('/search', [InvoiceController::class, 'search'])->name('invoice.search');

Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');

// Admin All route
Route::middleware(['auth'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');

        Route::get('/admin/profile', 'Profile')->name('admin.profile');

        Route::get('/edit/profile', 'EditProfile')->name('edit.profile');

        Route::post('/store/profile', 'StoreProfile')->name('store.profile');

        Route::get('/change/password', 'ChangePassword')->name('change.password');

        Route::post('/update/password', 'UpdatePassword')->name('update.password');
    });
});


// Frontend Page Layout and Content section-----------------------------------------------------------------------------------------------------------------------


// // Home Slide All route
Route::controller(HomeSliderController::class)->group(function () {
    Route::get('/home/slide', 'HomeSlider')->name('home.slide');
    Route::post('/update/slide', 'UpdateSlider')->name('update.slider');
});

// // About Page All route
Route::controller(AboutController::class)->group(function () {
    Route::get('/about/page', 'AboutPage')->name('about.page');
    Route::post('/update/about', 'UpdateAbout')->name('update.about');
    Route::get('/about', 'HomeAbout')->name('home.about');

    Route::get('/about/multi/image', 'AboutMultiImage')->name('about.multi.image');
    Route::post('/store/multi/image', 'StoreMultiImage')->name('store.multi.image');

    Route::get('/all/multi/image', 'AllMultiImage')->name('all.multi.image');
    Route::get('/edit/multi/image/{id}', 'EditMultiImage')->name('edit.multi.image');
    Route::post('/update/multi/image', 'UpdateMultiImage')->name('update.multi.image');
    Route::get('/delete/multi/image/{id}', 'DeleteMultiImage')->name('delete.multi.image');
});


// // Portfolio All route
Route::controller(PortfolioController::class)->group(function () {
    Route::get('/all/portfolio', 'AllPortfolio')->name('all.portfolio');
    Route::get('/add/portfolio', 'AddPortfolio')->name('add.portfolio');
    Route::post('/store/portfolio', 'StorePortfolio')->name('store.portfolio');

    Route::get('/edit/portfolio/{id}', 'EditPortfolio')->name('edit.portfolio');
    Route::post('/update/portfolio', 'UpdatePortfolio')->name('update.portfolio');

    Route::get('/delete/portfolio/{id}', 'DeletePortfolio')->name('delete.portfolio');

    Route::get('portfolio/details/{id}', 'PortfolioDetails')->name('portfolio.details');

    Route::get('portfolio', 'HomePortfolio')->name('home.portfolio');
});


// // Blog Category All route
Route::controller(BlogCategoryController::class)->group(function () {
    Route::get('/all/blog/category', 'AllBlogCategory')->name('all.blog.category');
    Route::get('/add/blog/category', 'AddBlogCategory')->name('add.blog.category');
    Route::post('/store/blog/category', 'StoreBlogCategory')->name('store.blog.category');

    Route::get('/edit/blog/category/{id}', 'EditBlogCategory')->name('edit.blog.category');
    Route::post('/update/blog/category/{id}', 'UpdateBlogCategory')->name('update.blog.category');

    Route::get('/delete/blog/category/{id}', 'DeleteBlogCategory')->name('delete.blog.category');
});


// // Blog All route
Route::controller(BlogController::class)->group(function () {
    Route::get('/all/blog', 'AllBlog')->name('all.blog');
    Route::get('/add/blog', 'AddBlog')->name('add.blog');
    Route::post('/store/blog', 'StoreBlog')->name('store.blog');

    Route::get('/edit/blog/{id}', 'EditBlog')->name('edit.blog');
    Route::post('/update/blog/{id}', 'UpdateBlog')->name('update.blog');

    Route::get('/delete/blog/{id}', 'DeleteBlog')->name('delete.blog');

    Route::get('/blog/details/{id}', 'BlogDetails')->name('blog.details');
    Route::get('/category/blog/{id}', 'CategoryBog')->name('category.blog');

    Route::get('/blog', 'HomeBlog')->name('home.blog');
});


// // Footer All route
Route::controller(FooterController::class)->group(function () {
    Route::get('/footer/setup', 'FooterSetup')->name('footer.setup');

    Route::post('/update/footer/', 'UpdateFooter')->name('update.footer');
});

Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'Contact')->name('contact.me');
    Route::post('/store', 'StoreMessage')->name('store.message');

    Route::get('/contact/message', 'ContactMessage')->name('contact.message');

    Route::get('/show/message/{id}', 'ShowMessage')->name('show.message');

    Route::get('/delete/message/{id}', 'DeleteMessage')->name('delete.message');
});


// Backend Inventory Management System section-----------------------------------------------------------------------------------------------------------------------

Route::middleware('auth')->group(function () {

    // Suppliers All route
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
        Route::get('/supplier/add', 'SupplierAdd')->name('supplier.add');

        Route::post('/supplier/store', 'SupplierStore')->name('supplier.store');
        Route::get('/supplier/view/{id}', 'SupplierView')->name('supplier.view');
        Route::get('/supplier/credit', 'SupplierCredit')->name('supplier.credit');
        Route::get('/credit/supplier/', 'CreditSupplier')->name('credit.supplier');

        Route::get('supplier/edit/invoice/{invoice_id}', 'SupplierEditInvoice')->name('supplier.edit.invoice');
        Route::post('supplier/update/invoice/{invoice_id}', 'SupplierUpdateInvoice')->name('supplier.update.invoice');

        Route::get('/supplier/edit/{id}', 'SupplierEdit')->name('supplier.edit');
        Route::post('/supplier/update', 'SupplierUpdate')->name('supplier.update');

        Route::get('/delete/supplier/{id}', 'DeleteSupplier')->name('supplier.delete');
    });


    //Customers All route
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/all', 'CustomerAll')->name('customer.all');
        Route::get('/customer/search', 'search')->name('customer.search');
        Route::get('/customer/allFilter', 'CustomerAllWithFilter')->name('customer.allwithfilter');
        Route::get('/customers/data',  'getCustomers')->name('customers.data');
        Route::get('/customer/add', 'CustomerAdd')->name('customer.add');
        Route::post('/customer/store', 'CustomerStore')->name('customer.store');
        Route::post('/customer/store/pos', 'CustomerStorePOS')->name('customer.storepos');


        Route::get('/customer/edit/{id}', 'CustomerEdit')->name('customer.edit');
        Route::get('/customer/view/{id}', 'CustomerView')->name('customer.view');
        Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');

        Route::get('/delete/customer/{id}', 'DeleteCustomer')->name('customer.delete');

        Route::get('/credit/customer/', 'CreditCustomer')->name('credit.customer');
        Route::get('/credit/customer/pdf/', 'CreditCustomerPdf')->name('credit.customer.pdf');

        Route::get('customer/edit/invoice/{invoice_id}', 'CustomerEditInvoice')->name('customer.edit.invoice');
        Route::post('customer/update/invoice/{invoice_id}', 'CustomerUpdateInvoice')->name('customer.update.invoice');

        Route::get('customer/invoice/details/{invoice_id}', 'CustomerInvoiceDetails')->name('customer.invoice.details.pdf');

        Route::get('paid/customer', 'PaidCustomer')->name('paid.customer');
        Route::get('paid/customer/print/pdf', 'PaidCustomerPrintPdf')->name('paid.customer.print.pdf');

        Route::get('customer/wise/report', 'CustomerWiseReport')->name('customer.wise.report');

        Route::get('customer/wise/credit/report', 'CustomerWiseCreditReport')->name('customer.wise.credit.report');
        Route::get('customer/wise/paid/report', 'CustomerWisePaidReport')->name('customer.wise.paid.report');
    });


    // Unit All route
    Route::controller(UnitController::class)->group(function () {
        Route::get('/unit/all', 'UnitAll')->name('unit.all');
        Route::get('/unit/add', 'UnitAdd')->name('unit.add');
        Route::post('/unit/store', 'UnitStore')->name('unit.store');

        Route::get('/unit/edit/{id}', 'UnitEdit')->name('unit.edit');
        Route::post('/unit/update', 'unitUpdate')->name('unit.update');

        Route::get('/delete/unit/{id}', 'DeleteUnit')->name('unit.delete');
    });

    // Category All route
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/all', 'CategoryAll')->name('category.all');
        Route::get('/category/add', 'CategoryAdd')->name('category.add');
        Route::post('/category/store', 'CategoryStore')->name('category.store');

        Route::get('/category/edit/{id}', 'CategoryEdit')->name('category.edit');
        Route::post('/category/update', 'CategoryUpdate')->name('category.update');

        Route::get('/delete/category/{id}', 'DeleteCategory')->name('category.delete');
    });

    // Products All route
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/all', 'ProductAll')->name('product.all');
        Route::get('/product/add', 'ProductAdd')->name('product.add');
        Route::post('/product/store', 'ProductStore')->name('product.store');

        Route::get('/product/edit/{id}', 'ProductEdit')->name('product.edit');
        Route::post('/product/update', 'ProductUpdate')->name('product.update');

        Route::get('/delete/product/{id}', 'DeleteProduct')->name('product.delete');
    });


    // Purchase All route
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/purchase/all', 'PurchaseAll')->name('purchase.all');
        Route::get('/purchase/add', 'PurchaseAdd')->name('purchase.add');
        Route::post('/purchase/store', 'PurchaseStore')->name('purchase.store');
        Route::get('/purchase/detials/{id}', 'PurchaseDetials')->name('purchase.details');
        Route::get('/delete/purchase/{id}', 'DeletePurchase')->name('purchase.delete');
        Route::get('/delete/purchaseAfterApprove/{purchase_no}', 'PurchaseDelete')->name('purchase.deleteafterapprove');

        Route::get('/purchase/pending', 'PurchasePending')->name('purchase.pending');
        Route::get('/purchase/approve/{id}', 'PurchaseApprove')->name('purchase.approve');

        Route::get('/daily/purchase/report', 'DailyPurchaseReport')->name('daily.purchase.report');
        Route::get('/daily/purchase/pdf', 'DailyPurchasePdf')->name('daily.purchase.pdf');
    });


    // Default All route
    Route::controller(DefaultController::class)->group(function () {
        Route::get('/get-category', 'GetCategory')->name('get-category');
        Route::get('/get-product', 'GetProduct')->name('get-product');
        Route::get('/check-product-stock', 'GetStock')->name('check-product-stock');
    });
    Route::get('/customer/{id}/invoices/pdf', [CustomerController::class, 'generateInvoicesPDF'])->name('customer.invoices.pdf');

    // Invoice All route
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all');
        Route::get('/invoice/add', 'InvoiceAdd')->name('invoice.add');
        Route::get('/invoice/addnew', 'InvoiceAddNew')->name('invoice.addnew');
        Route::get('/invoice/addnew',  'create')->name('invoice.create');

        Route::post('/invoice/store', 'InvoiceStore')->name('invoice.store');

        Route::get('/invoice/pending/list', 'PendingList')->name('invoice.pending');
        Route::get('/delete/invoice/{id}', 'DeleteInvoice')->name('invoice.delete');
        Route::get('/delete/invoiceAfterApprove/{invoice_no}', 'InvoiceDelete')->name('invoice.deleteafterapprove');
        Route::get('/invoice/approve/{id}', 'InvoiceApprove')->name('invoice.approve');
        Route::post('/approve/store/{id}', 'ApprovalStore')->name('approval.store');

        Route::get('/invoice/print/list', 'PrintInvoiceList')->name('print.invoiceList');
        Route::get('/invoice/print/{id}', 'PrintInvoice')->name('print.invoice');

        Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');

        Route::get('/daily/invoice/pdf', 'DailyInvoicePdf')->name('daily.invoice.pdf');
    });


    // Stock All route
    Route::controller(StockController::class)->group(function () {
        Route::get('/stock/report', 'StockReport')->name('stock.report');
        Route::get('/stock/addproduct', 'StockAddproduct')->name('stock.addproduct');
        Route::post('/stock/store', 'store')->name('stock.store');

        Route::get('/stock/report/pdf', 'StockReportPdf')->name('stock.report.pdf');

        Route::get('/stock/supplier/report', 'StockSupplierReport')->name('stock.supplier.report');

        Route::get('/supplier/wise/pdf', 'SupplierWisePdf')->name('supplier.wise.pdf');
        Route::get('/product/wise/pdf', 'ProductWisePdf')->name('product.wise.pdf');
        Route::post('/stock/update/{id}', 'updateStockQuantity')->name('stock.update');
        Route::post('/stock/zero/{id}', 'zeroStockQuantity')->name('stock.zero');
    });

    // expense All route
    Route::controller(ExpenseController::class)->group(function () {
        Route::get('/expense/all', 'ExpenseAll')->name('expense.all');
        Route::get('/expense/create', 'create')->name('expense.create');
        Route::get('/expense/createcategory', 'createcategory')->name('expense.createcategory');
        Route::get('/expense/editcategory/{id}', 'editcategory')->name('expense.editcategory');
        Route::get('/expense/deletecategory/{id}', 'deletecategory')->name('expense.deletecategory');

        Route::post('/expense/store', 'store')->name('expense.store');
        Route::get('/expense/edit/{id}', 'edit')->name('expense.edit');
        Route::get('/expense/delete/{id}', 'deleteExpense')->name('expense.delete');
        Route::post('/expense/update/{id}', [ExpenseController::class, 'update'])->name('expense.update'); // توجيه لحفظ التعديلات

        Route::post('/expense/createcategory', 'storecategory')->name('expense.storecategory');
        Route::post('/expense/updatecategory/{id}', 'updatecategory')->name('expense.updatecategory');

        Route::get('/expense/category', 'category')->name('expense.category');
        Route::get('/expense/print/list', 'printList')->name('expense.printList');
        Route::get('/expense/print/{id}', 'printExpense')->name('expense.print');
        Route::get('/daily/expense/report', 'DailyExpenseReport')->name('daily.expense.report');
        Route::get('/daily/expense/pdf', 'DailyExpensePdf')->name('daily.expense.pdf');
    });


    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employee/index', 'index')->name('employee.index');
        Route::get('/employee/salary/{id}', 'salarydetails')->name('employee.salarydetails');

        Route::post('/employee/store', 'store')->name('employee.store');
        Route::get('/employee/salares', 'salare')->name('employee.salares');
        Route::get('/employee/presenceabsence', 'presenceabsence')->name('employee.presenceabsence');
        Route::get('/employee/report', 'report')->name('employee.report');
        Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');


        Route::post('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');

        Route::get('/daily/employee/pdf', 'DailyEmployeePdf')->name('daily.employee.pdf');
    });
    Route::controller(SalaryController::class)->group(function () {


        Route::get('/employee/salary/{id}', 'show')->name('employee.salarydetails');
        Route::post('/salary-details', 'store')->name('salary-details.store');
        Route::put('/salary-details/{id}/status',  'updateStatus')->name('salary-details.updateStatus');
    });
    Route::controller(PartialPaymentController::class)->group(function () {


        Route::get('/partialpayments/create/{id}', 'create')->name('partialpayments.create');
        Route::post('/partialpayments', 'store')->name('partialpayments.store');
        Route::get('/partial-payments/{id}/edit', 'edit')->name('partialpayments.edit');
        Route::put('/partial-payments/{id}', 'update')->name('partialpayments.update');
    });
});

Route::prefix('attendance')->group(function () {
    Route::get('/attendance', [EmployeeAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [EmployeeAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [EmployeeAttendanceController::class, 'store'])->name('attendance.store');
});
