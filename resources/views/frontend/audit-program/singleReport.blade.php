<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexo - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    Audit Program Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://dms.mydemosoftware.com/user/images/logo1.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Audit Program No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>
                    <tr>  {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>
                        <th class="w-20">Date Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">@if($data->general_initiator_group){{ $data->general_initiator_group }} @else Not Applicable @endif</td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">@if($data->division_id){{ $data->division_id }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" colspan="3">
                            @if($data->short_description){{ $data->short_description }}@else Not Applicable @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Due Date</th>
                        <td class="w-80" colspan="3"> @if($data->due_date){{ $data->due_date }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Type</th>
                        <td class="w-30">@if($data->type){{ $data->type }}@else Not Applicable @endif</td>
                        <th class="w-20">Year</th>
                        <td class="w-30">@if($data->year){{ $data->year }}@else Not Applicable @endif</td>
                        <th class="w-20">Quarter</th>
                        <td class="w-30">@if($data->Quarter){{ $data->Quarter }}@else Not Applicable @endif</td>
                    </tr>
                    {{--  <tr>
                        <th class="w-20">Supporting Documents</th>
                        <td class="w-80" colspan="3">Document_Name.pdf</td>
                    </tr>  --}}
                </table>
            </div>

            <div class="block">
                <div class="head">

                    <table>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80">@if($data->comments){{ $data->comments }}@else Not Applicable @endif</td>
                        </tr>

                        <tr>
                            <th class="w-20">Description</th>
                            <td class="w-80">@if($data->description){{ $data->description }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Attached Files
                            </th>
                            <td class="w-80">@if($data->attachments)<a href="{{asset('upload/document/',$data->attachments)}}">{{ $data->attachments }}</a>@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Related URl
                            </th>
                            <td class="w-80">@if($data->related_url){{ $data->related_url }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Suggested Audits
                            </th>
                            <td class="w-80">@if($data->suggested_audits){{ $data->suggested_audits }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Zone
                            </th>
                            <td class="w-80">@if($data->Zone){{ $data->zone }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Country
                            </th>
                            <td class="w-80">@if($data->country){{ $data->country }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">City
                            </th>
                            <td class="w-80">@if($data->City){{ $data->City }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">State/District
                            </th>
                            <td class="w-80">@if($data->state_district){{ $data->state_district }}@else Not Applicable @endif</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Audit Program
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">Auditees</th>
                            <th class="w-20">Date Start	</th>
                            <th class="w-20">Date End	</th>
                            <th class="w-20">Lead Investigator	</th>
                            <th class="w-20">Comment</th>

                        </tr>

                    </table>
                </div>

            </div>



            {{--  <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Plan Proposed By
                        </th>
                        <td class="w-30">{{ $data->plan_proposed_by }}</td>
                        <th class="w-20">
                            Plan Proposed On</th>
                        <td class="w-30">{{ $data->plan_proposed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Plan Approved By
                        </th>
                        <td class="w-30">{{ $data->plan_approved_by }}</td>
                        <th class="w-20">
                            Plan Approved On</th>
                        <td class="w-30">{{ $data->Plan_approved_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA More Info Required By
                        </th>
                        <td class="w-30">{{ $data->qa_more_info_required_by }}</td>
                        <th class="w-20">
                            QA More Info Required On</th>
                        <td class="w-30">{{ $data->qa_more_info_required_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Cancelled By
                        </th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">
                            Cancelled On</th>
                        <td class="w-30">{{ $data->cancelled_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Completed By
                        </th>
                        <td class="w-30">{{ $data->completed_by }}</td>
                        <th class="w-20">
                            Completed On</th>
                        <td class="w-30">{{ $data->completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Approved By</th>
                        <td class="w-30">{{ $data->approved_by }}</td>
                        <th class="w-20">Approved On</th>
                        <td class="w-30">{{ $data->approved_on }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Rejected By</th>
                        <td class="w-30">{{ $data->rejected_by }}</td>
                        <th class="w-20">Rejected On</th>
                        <td class="w-30">{{ $data->rejected_on }}</td>
                    </tr>

                </table>
            </div>  --}}
        </div>
    </div>

    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
