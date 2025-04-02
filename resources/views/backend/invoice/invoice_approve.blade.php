@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تاكيد الفاتورة</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        @php
            $payment = App\Models\Payment::where('invoice_id',$invoice->id)->first();
        @endphp


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{route('invoice.pending')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;"><i class="fa fa-list"> <span style="font-family: sans-serif; font-weight: normal;">Pending Invoice List</span></i></a>
                    <br><br>

                    <h4>Invoice No:#{{ $invoice->invoice_no }} - {{ date('d-m-Y',strtotime($invoice->date)) }}</h4>
                    <br><br>

                    <table class="table mb-0" width="100%">
                        <thead>
                            <tr>
                                <th><p>معلومات الزبون</p></th>
                                <th><p>الاسم : <strong>{{ $payment['customer']['name'] }}</strong></p></th>
                                <th><p>رقم الهاتف: <strong>{{ $payment['customer']['mobile_no'] }}</strong></p></th>
                                <th><p>الايميل: <strong>{{ $payment['customer']['email'] }}</strong></p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td colspan="3">
                                    <p>الملاحظات: <strong>{{ $invoice->description }}</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <form method="post" action="{{ route('approval.store',$invoice->id) }}">
                        @csrf
                        <table border="1" class="table mb-0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">الفئة</th>
                                    <th class="text-center">اسم المنتج</th>
                                    <th class="text-center" style="background-color:#6C757D;color:white">المخزون</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">سعر الوحدة</th>
                                    <th class="text-center">المبلغ الاجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_sum='0'
                                @endphp

                                @foreach($invoice['invoice_details'] as $key => $details)
                                <tr>

                                    <input type="hidden" name="category_id[]" value="{{ $details->categoy_id }}">
                                    <input type="hidden" name="product_id[]" value="{{ $details->product_id }}">
                                    <input type="hidden" name="selling_qty[{{ $details->id }}]" value="{{ $details->selling_qty }}">

                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td class="text-center">{{ $details['category']['name'] }}</td>
                                    <td class="text-center">{{ $details['product']['name'] }}</td>
                                    <td class="text-center" style="background-color:#6C757D;color:white">{{ $details['product']['quantity'] }}</td>
                                    <td class="text-center">{{ $details->selling_qty }}</td>
                                    <td class="text-center">{{ $details->unit_price }}</td>
                                    <td class="text-center">{{ $details->selling_price }}</td>
                                </tr>
                                @php
                                $total_sum += $details->selling_price
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="6">المبلغ</td>
                                    <td>{{ $total_sum }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6">الخصم</td>
                                    <td>{{ $payment->discount_amount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6">المبلغ المدفوع</td>
                                    <td>{{ $payment->paid_amount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6">المبلغ المستحق</td>
                                    <td>{{ $payment->due_amount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6">المبلغ الاجمالي </td>
                                    <td>{{ $payment->total_amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br>

                        <button type="submit" class="btn btn-info">تأكيد الفاتورة</button>
                    </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->


    </div> <!-- container-fluid -->
</div>

@endsection
