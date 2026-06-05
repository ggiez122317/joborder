@extends('layouts.log')

@section('title', $title)

@section('content')
  <div class="container-fluid mt-1">
    <div class="row mt-3">
        <div class="col-12 mb-3">
        <h5 class="p-1 mb-0">
            <i class="icon left la la-tasks"></i> Record Logs</span>
        </h5>
        <div class="card">
            <div class="card-body px-1 py-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-1">
                <thead>
                    <tr style="font-weight: bold;">
                    <td nowrap>Date/Time</td>
                    <td nowrap>Workstation</td>
                    <td nowrap>User</td>
                    <td nowrap>Module</td>
                    <td nowrap>Operation</td>
                    <td nowrap>Log</td>
                    </tr>
                </thead>
                <tbody>
                                    <tr>
                        <td colspan="7" align="center"> <i>No records found!</i></td>
                    </tr>
                                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
        <h5 class="p-1 mb-0">
            <i class="icon left la la-tasks"></i> Field Logs</span>
        </h5>
        <div class="card">
            <div class="card-body px-1 py-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-1">
                <thead>
                    <tr style="font-weight: bold;">
                    <td nowrap>Date/Time</td>
                    <td nowrap>Workstation</td>
                    <td nowrap>User</td>
                    <td nowrap>Operation</td>
                    <td nowrap>Field</td>
                    <td nowrap>Old Value</td>
                    <td nowrap>New Value</td>
                    </tr>
                </thead>
                <tbody>
                                    <tr>
                        <td colspan="7" align="center"> <i>No records found!</i></td>
                    </tr>
                                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
  </div>    
@endsection

@section('scripts')
    <script>


    </script>
@endsection