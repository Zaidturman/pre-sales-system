
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">القائمة</li>
                

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>لوحة القيادة </span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow  waves-effect">
                        <i class="ri-archive-fill"></i>
                        <span>ادارة الموردين</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('supplier.all')}}">جميع الموردين</a></li>
                        <li><a href="{{route('supplier.credit')}}">مستحقات الموردين</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-account-circle-fill"></i>
                        <span>ادارة الزبائن</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('customer.all')}}">جميع الزبائن</a></li>
                        <li><a href="{{route('credit.customer')}}">ديون الزبائن</a></li>
                        <li><a href="{{route('paid.customer')}}">مدفوعات الزبائن</a></li>
                        <li><a href="{{route('customer.wise.report')}}">تقرير حسب العملاء</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-home-gear-fill"></i>
                        <span>ادارة الوحدات</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('unit.all')}}">جميع الوحدات</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-checkbox-multiple-blank-fill"></i>
                        <span>ادارة الفئات</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('category.all')}}">جميع الفئات </a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-dropbox-fill"></i>
                        <span>ادارة البضائع</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('product.all')}}">جميع البضائع</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-money-dollar-circle-fill"></i>
                        <span>ادارة المشتريات </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('purchase.all')}}">جميع المشتريات </a></li>
                        <li><a href="{{route('purchase.pending')}}">الموافقة على المشتريات </a></li>
                        <li><a href="{{route('daily.purchase.report')}}">تقرير المشتريات اليومية </a></li>
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-file-copy-2-fill"></i>
                        <span>ادارة الفواتير</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('invoice.all')}}">جميع الفواتير</a></li>
                        <li><a href="{{route('print.invoiceList')}}">طباعة فاتورة </a></li>
                        <li><a href="{{route('daily.invoice.report')}}">تقرير الفواتير اليومية</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-wallet-3-fill"></i>
                        <span>ادارة المصاريف</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('expense.all')}}">جميع المصاريف</a></li>
                        <li><a href="{{route('expense.category')}}">فئات المصاريف </a></li>
                        <li><a href="{{route('expense.create')}}">اضافة المصاريف </a></li>

                        <li><a href="{{route('expense.printList')}}">طباعة المصاريف </a></li>

                        <li><a href="{{route('daily.expense.report')}}">تقرير المصاريف اليومية</a></li>
                    </ul>
                </li>
                <li class="menu-title">الموظفين</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-team-fill"></i>
                        <span>ادارة الموظفين</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('employee.index')}}">جميع الموظفيين</a></li>
                        <li><a href="{{route('employee.salares')}}">الرواتب</a></li>
                        <li><a href="{{route('attendance.index')}}"> الحضور والغياب </a></li>

                        <li><a href="{{route('employee.report')}}">تقرير الموظفين اليومية</a></li>
                    </ul>
                </li>

                <li class="menu-title">المخزن</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-inbox-archive-fill"></i>
                        <span>ادارة المخزن </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('stock.report') }}">تقرير المخزن</a></li>
                        <li><a href="{{ route('stock.addproduct') }}">اضافة منتجات الى المخزن</a></li>
                        <li><a href="{{ route('stock.supplier.report') }}">تقرير المنتجات</a></li>
                    </ul>
                </li>



                <!-- Page Layout Start -->
                {{-- <li class="menu-title">Pages</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-layout-3-line"></i>
                                    <span>Home Slide Setup</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{route('home.slide')}}">Home Slide</a></li>
            </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>About Page Setup</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('about.page')}}">About Page</a></li>
                    <li><a href="{{route('about.multi.image')}}">About Multi Image</a></li>
                    <li><a href="{{route('all.multi.image')}}">All Multi Image</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>Portfolio Page Setup</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('all.portfolio')}}">All Portfolio</a></li>
                    <li><a href="{{route('add.portfolio')}}">Add Portfolio</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>Blog Category</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('all.blog.category')}}">All Blog Category</a></li>
                    <li><a href="{{route('add.blog.category')}}">Add Blog Category</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>Blog Page</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('all.blog')}}">All Blog</a></li>
                    <li><a href="{{route('add.blog')}}">Add Blog</a></li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>Footer Page</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('footer.setup')}}">Footer Setup</a></li>
                </ul>
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-layout-3-line"></i>
                    <span>Contact Message</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{route('contact.message')}}">Contact Message</a></li>
                </ul>
            </li> --}}
            <!-- Page Layout End -->




            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>