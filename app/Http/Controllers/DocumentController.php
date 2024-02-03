<?php

namespace App\Http\Controllers;

use App\Imports\DocumentsImport;
use App\Models\Annexure;
use App\Models\Department;
use App\Models\Division;
use App\Models\Document;
use App\Models\DocumentContent;
//use App\Models\ContentsDocument;
use App\Models\DocumentHistory;
use App\Models\DocumentLanguage;
use App\Models\DocumentSubtype;
use App\Models\DocumentTraining;
//use App\Models\DocumentTraningInformation;
use App\Models\DocumentType;
use App\Models\DownloadControl;
use App\Models\DownloadHistory;
use App\Models\Grouppermission;
use App\Models\Keyword;
use App\Models\OpenStage;
use App\Models\PrintControl;
use App\Models\PrintHistory;
use App\Models\Process;
use App\Models\RoleGroup;
use App\Models\SetDivision;
use App\Models\Stage;
use App\Models\StageManage;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function division(Request $request)
    {
        

        $new = new SetDivision;
        $new->division_id = $request->division_id;
        $new->process_id = $request->process_id;
        $new->user_id = Auth::user()->id;
        $new->save();

        return redirect()->route('documents.create');
    }
    public function division_old(Request $request)
    {
        //  $request->dd();
        //  return $request;
 

        $new = new Document;
        $new->originator_id = $request->originator_id;
        $new->division_id = $request->division_id;
        $new->process_id = $request->process_id;
        $new->record = $request->record;
        $new->revised = $request->revised;
        $new->revised_doc = $request->revised_doc;
        $new->document_name = $request->document_name;
        $new->short_description = $request->short_description;
        $new->due_dateDoc = $request->due_dateDoc;
        $new->description = $request->description;
        //$new->notify_to = $request->notify_to;
        $new->reference_record = $request->reference_record;
        $new->department_id = $request->department_id;
        $new->document_type_id = $request->document_type_id;
        $new->document_subtype_id = $request->document_subtype_id;
        $new->document_language_id = $request->document_language_id;
        $new->keywords = $request->keywords;
        $new->effective_date = $request->effective_date;
        $new->next_review_date = $request->next_review_date;
        $new->review_period = $request->review_period;
        $new->attach_draft_doocument = $request->attach_draft_doocument;
        $new->attach_effective_docuement = $request->attach_effective_docuement;
        $new->reviewers = $request->reviewers;
        $new->approvers = $request->approvers;
        $new->reviewers_group = $request->reviewers_group;
        $new->approver_group = $request->approver_group;
        $new->revision_summary = $request->revision_summary;
        $new->stage = $request->stage;
        $new->status = $request->status;
        $new->document = $request->document;
        $new->revision = $request->revision;
        $new->revision_policy = $request->revision_policy;
        $new->training_required = $request->training_required;
        $new->trainer = $request->trainer;
        $new->comments = $request->comments;
       
        $new->user_id = Auth::user()->id;
        $new->save();

        return redirect()->route('documents.create');
    }

       public function dcrDivision() {
        return redirect()->route('change-control.create');
       }
    public function index()
    {
        //

        $count = Document::where('documents.originator_id', Auth::user()->id)->count();
        $documents = Document::join('users', 'documents.originator_id', 'users.id')->join('document_types', 'documents.document_type_id', 'document_types.id')
            ->join('divisions', 'documents.division_id', 'divisions.id')
            ->select('documents.*', 'users.name as originator_name', 'document_types.name as document_type_name', 'divisions.name as division_name')->where('documents.originator_id', Auth::user()->id)->orderByDesc('documents.id')->paginate(10);

        return view('frontend.documents.index', compact('documents', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $division = SetDivision::where('user_id', Auth::id())->latest()->first();
        $division->dname = Division::where('id', $division->division_id)->value('name');
        $division->pname = Process::where('id', $division->process_id)->value('process_name');
        $users = User::all();
        if (! empty($users)) {
            foreach ($users as $data) {
                $data->role = RoleGroup::where('id', $data->role)->value('name');
            }
        }
        $document = Document::all();
        if (! empty($document)) {
            foreach ($document as $temp) {
                if (! empty($temp)) {
                    $temp->division = Division::where('id', $temp->division_id)->value('name');
                    $temp->typecode = DocumentType::where('id', $temp->document_type_id)->value('typecode');
                    $temp->year = Carbon::parse($temp->created_at)->format('Y');
                }
            }
        }
        $departments = Department::all();
        $documentTypes = DocumentType::all();
        $documentsubTypes = DocumentSubtype::all();
        $documentLanguages = DocumentLanguage::all();
        $reviewer = User::where('role', 2)->get();
        $trainer = User::where('role', 6)->get();
        $approvers = User::where('role', 1)->get();
        $reviewergroup = Grouppermission::where('role_id', 2)->get();
        $approversgroup = Grouppermission::where('role_id', 1)->get();
        // Retrieve the current counter value
        $counter = DB::table('record_numbers')->value('counter');

        // Generate the record number with leading zeros
        $recordNumber = str_pad($counter + 1, 5, '0', STR_PAD_LEFT);

        $user = User::all();

        return view('frontend.documents.create', compact(
            'departments',
            'documentTypes',
            'documentLanguages',
            'user',
            'reviewer',
            'approvers',
            'reviewergroup',
            'approversgroup',
            'trainer',
            'document',
            'users',
            'recordNumber',
            'division',
            'documentsubTypes'
        ));
    }

    // documentExportPDF
    public function documentExportPDF()
    {
        $documents = Document::all();
        dd($documents);
    }

    // documentExportEXCEL
    public function documentExportEXCEL()
    {
        return Excel::download(new DocumentsExport, 'documents.csv');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->dd();
        //  return $request;


        if ($request->submit == 'save') {

            $document = new Document();
            $division = SetDivision::where('user_id', Auth::id())->latest()->first();
            $document->division_id = $division->division_id;
            $document->process_id = $division->process_id;
            $document->record = DB::table('record_numbers')->value('counter') + 1;
            $document->originator_id = Auth::id();
            $document->document_name = $request->document_name;
            $document->short_description = $request->short_desc;
            $document->description = $request->description;
            $document->stage = 1;
            $document->status = Stage::where('id', 1)->value('name');
            $document->due_dateDoc = $request->due_dateDoc;
            $document->department_id = $request->department_id;
            $document->document_type_id = $request->document_type_id;
            $document->document_subtype_id = $request->document_subtype_id;
            $document->document_language_id = $request->document_language_id;
            $document->effective_date = $request->effective_date;
            $document->next_review_date = $request->next_review_date;
            $document->review_period = $request->review_period;
            $document->training_required = $request->training_required;
            $document->trainer = $request->trainer;
            $document->comments = $request->comments;
            //$document->purpose = $request->purpose;

            if ($request->keywords) {
                $document->keywords = implode(',', $request->keywords);
            }
            if ($request->notify_to) {
                $document->notify_to = implode(',', $request->notify_to);
            }
            if ($request->reference_record) {
                $document->reference_record = implode(',', $request->reference_record);
            }

            if ($request->hasfile('attach_draft_doocument')) {

                $image = $request->file('attach_draft_doocument');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $document->attach_draft_doocument = $image_name;
            }

            if ($request->hasfile('attach_effective_docuement')) {

                $image = $request->file('attach_effective_docuement');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $document->attach_effective_docuement = $image_name;
            }

            $document->revision_summary = $request->revision_summary;
            if (! empty($request->reviewers)) {
                $document->reviewers = implode(',', $request->reviewers);
            }
            if (! empty($request->approvers)) {
                $document->approvers = implode(',', $request->approvers);
            }
            if (! empty($request->reviewers_group)) {
                $document->reviewers_group = implode(',', $request->reviewers_group);
            }
            if (! empty($request->approver_group)) {
                $document->approver_group = implode(',', $request->approver_group);
            }
            $document->save();
            // Retrieve the current counter value
            $counter = DB::table('record_numbers')->value('counter');

            // Generate the record number with leading zeros
            $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);

            // Increment the counter value
            $newCounter = $counter + 1;
            DB::table('record_numbers')->update(['counter' => $newCounter]);
            if (! empty($request->keywords)) {
                foreach ($request->keywords as $key) {
                    $keyword = new Keyword();
                    $keyword->user_id = Auth::user()->id;
                    $keyword->document_id = $document->id;
                    $keyword->keyword = $key;
                    $keyword->save();
                }



                
            }
            if ($request->training_required == 'yes') {
                $trainning = new DocumentTraining();
                $trainning->document_id = $document->id;
                $trainning->trainer = $request->trainer;
                $trainning->cbt = $request->cbt;
                $trainning->type = $request->type;
                $trainning->comments = $request->comments;
                $trainning->save();
            }

            $annexure = new Annexure();
            $annexure->document_id = $document->id;
            if (! empty($request->serial_number)) {
                $annexure->sno = serialize($request->serial_number);
            }
            if (! empty($request->annexure_number)) {
                $annexure->annexure_no = serialize($request->annexure_number);
            }
            if (! empty($request->annexure_data)) {
                $annexure->annexure_title = serialize($request->annexure_data);
            }
            $annexure->save();

            $content = new DocumentContent();
            $content->document_id = $document->id;
            $content->purpose = $request->purpose;
            $content->scope = $request->scope;
            $content->procedure = $request->procedure;
            if (! empty($request->materials_and_equipments)) {
                $content->materials_and_equipments = serialize($request->materials_and_equipments);
            }
            if (! empty($request->responsibility)) {
                $content->responsibility = serialize($request->responsibility);
            }
            if (! empty($request->abbreviation)) {
                $content->abbreviation = serialize($request->abbreviation);
            }
            if (! empty($request->defination)) {
                $content->defination = serialize($request->defination);
            }
            if (! empty($request->reporting)) {
                $content->reporting = serialize($request->reporting);
            }
            if ($request->hasfile('references')) {

                $image = $request->file('references');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $content->references = $image_name;
            }

            if (! empty($request->annexuredata)) {
                $content->annexuredata = serialize($request->annexuredata);
            }

            $content->save();

            toastr()->success('Document created');

            return redirect()->route('documents.index');
        } else {
            toastr()->error('Not working');

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $users = User::all();
        if (! empty($users)) {
            foreach ($users as $data) {
                $data->role = RoleGroup::where('id', $data->role)->value('name');
            }
        }
        $document_data = Document::all();
        if (! empty($document_data)) {
            foreach ($document_data as $temp) {
                if (! empty($temp)) {
                    $temp->division = Division::where('id', $temp->division_id)->value('name');
                    $temp->typecode = DocumentType::where('id', $temp->document_type_id)->value('typecode');
                    $temp->year = Carbon::parse($temp->created_at)->format('Y');
                }
            }
        }
        $print_history = PrintHistory::join('users', 'print_histories.user_id', 'users.id')->select('print_histories.*', 'users.name as user_name')->where('document_id', $id)->get();
        $document = Document::join('users', 'documents.originator_id', 'users.id')->leftjoin('document_types', 'documents.document_type_id', 'document_types.id')
            ->join('divisions', 'documents.division_id', 'divisions.id')->leftjoin('departments', 'documents.department_id', 'departments.id')
            ->select('documents.*', 'users.name as originator_name', 'document_types.name as document_type_name', 'divisions.name as division_name', 'departments.name as dept_name')->where('documents.id', $id)->first();
        $document->date = Carbon::parse($document->created_at)->format('d-M-Y');
        $document['document_content'] = DocumentContent::where('document_id', $id)->first();
        $document['division'] = Division::where('id', $document->division_id)->value('name');
        $year = Carbon::parse($document->created_at)->format('Y');
        $trainer = User::where('role', 6)->get();
        $trainingDoc = DocumentTraining::where('document_id', $id)->first();
        $history = DocumentHistory::where('document_id', $id)->get();
        $documentsubTypes = DocumentSubtype::all();

        // $history = [];
        // foreach($historydata as $temp){
        //     array_push($history,$temp);
        // }
        $keywords = Keyword::where('document_id', $id)->get();
        $annexure = Annexure::where('document_id', $id)->first();

        $signature = StageManage::where('document_id', $id)->get();
        $reviewer = User::where('role', 2)->get();
        $approvers = User::where('role', 1)->get();
        $reviewergroup = Grouppermission::where('role_id', 2)->get();
        $approversgroup = Grouppermission::where('role_id', 1)->get();
        $user = User::all();
        $departments = Department::all();
        $documentTypes = DocumentType::all();
        $documentLanguages = DocumentLanguage::all();

        return view('frontend.documents.edit', compact(
            'document',
            'departments',
            'documentTypes',
            'documentLanguages',
            'reviewer',
            'approvers',
            'reviewergroup',
            'approversgroup',
            'year',
            'print_history',
            'signature',
            'trainer',
            'document_data',
            'users',
            'trainingDoc',
            'history',
            'keywords',
            'annexure',
            'documentsubTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {

        if ($request->submit == 'save') {
            $lastDocument = Document::find($id);
            $lastContent = DocumentContent::where('document_id', $id)->first();
            $lastTraining = DocumentTraining::where('document_id', $id)->first();
            $document = Document::find($id);
            $document->document_name = $request->document_name;
            $document->short_description = $request->short_desc;
            $document->description = $request->description;
            $document->due_dateDoc = $request->due_dateDoc;
            $document->department_id = $request->department_id;
            $document->document_type_id = $request->document_type_id;
            $document->document_subtype_id = $request->document_subtype_id;
            $document->document_language_id = $request->document_language_id;
            $document->effective_date = $request->effective_date;
            $document->next_review_date = $request->next_review_date;
            $document->review_period = $request->review_period;
            $document->training_required = $request->training_required;
            $document->attach_draft_doocument = $request->attach_draft_doocument;

            if ($request->keywords) {
                $document->keywords = implode(',', $request->keywords);
            }
            if ($request->notify_to) {
                $document->notify_to = implode(',', $request->notify_to);
            }
            if ($request->reference_record) {
                $document->reference_record = implode(',', $request->reference_record);
            }
            

            if ($request->hasfile('attach_draft_doocument')) {

                $image = $request->file('attach_draft_doocument');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $document->attach_draft_doocument = $image_name;
            }

            if ($request->hasfile('attach_effective_docuement')) {

                $image = $request->file('attach_effective_docuement');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $document->attach_effective_docuement = $image_name;
            }
            $document->revision_summary = $request->revision_summary;
            if (! empty($request->reviewers)) {
                $document->reviewers = implode(',', $request->reviewers);
            }
            if (! empty($request->approvers)) {
                $document->approvers = implode(',', $request->approvers);
            }
            if (! empty($request->reviewers_group)) {
                $document->reviewers_group = implode(',', $request->reviewers_group);
            }
            if (! empty($request->approver_group)) {
                $document->approver_group = implode(',', $request->approver_group);
            }
            $document->update();
            if (! empty($request->keywords)) {
                foreach ($request->keywords as $key) {
                    $keyword = new Keyword();
                    $keyword->user_id = Auth::user()->id;
                    $keyword->document_id = $document->id;
                    $keyword->keyword = $key;
                    $keyword->save();
                }

            }

            if ($request->training_required == 'yes') {
                $trainning = DocumentTraining::where('document_id', $id)->first();
                if (empty($trainning)) {
                    $trainning = new DocumentTraining();
                    $trainning->document_id = $document->id;
                    $trainning->trainer = $request->trainer;
                    $trainning->cbt = $request->cbt;
                    $trainning->type = $request->type;
                    $trainning->comments = $request->comments;
                    $trainning->save();
                } else {
                    $trainning->document_id = $document->id;
                    $trainning->trainer = $request->trainer;
                    $trainning->cbt = $request->cbt;
                    $trainning->type = $request->type;
                    $trainning->comments = $request->comments;
                    $trainning->update();
                }
            }
            if ($lastDocument->document_name != $document->document_name || ! empty($request->document_name_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Document Name';
                $history->previous = $lastDocument->document_name;
                $history->current = $document->document_name;
                $history->comment = $request->document_name_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
                $changeControl = OpenStage::where('title', $lastDocument->document_name)->first();
                if($changeControl){
                    $changeControl->title = $document->document_name;
                    $changeControl->update();
                }
            }
            if ($lastDocument->short_description != $document->short_description || ! empty($request->short_desc_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Short Description';
                $history->previous = $lastDocument->short_description;
                $history->current = $document->short_description;
                $history->comment = $request->short_desc_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->due_dateDoc != $document->due_dateDoc || ! empty($request->due_dateDoc_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Due Date';
                $history->previous = $lastDocument->due_dateDoc;
                $history->current = $document->due_dateDoc;
                $history->comment = $request->due_dateDoc_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->description != $document->description || ! empty($request->description_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Description';
                $history->previous = $lastDocument->description;
                $history->current = $document->description;
                $history->comment = $request->description_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }

           
            // if ($lastDocument->department_id != $document->department_id || ! empty($request->department_id_comment)) {
            //     $history = new DocumentHistory;
            //     $history->document_id = $id;
            //     $history->activity_type = 'Department';
            //     $history->previous = Department::where('id', $lastDocument->department_id)->value('name');
            //     $history->current = Department::where('id', $document->department_id)->value('name');
            //     $history->comment = $request->department_id_comment;
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $lastDocument->status;
            //     $history->save();
            // }
            // if ($lastDocument->document_type_id != $document->document_type_id || ! empty($request->document_type_id_comment)) {
            //     $history = new DocumentHistory;
            //     $history->document_id = $id;
            //     $history->activity_type = 'Document';
            //     $history->previous = DocumentType::where('id', $lastDocument->document_type_id)->value('name');
            //     $history->current = DocumentType::where('id', $document->document_type_id)->value('name');
            //     $history->comment = $request->document_type_id_comment;
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $lastDocument->status;
            //     $history->save();
            // }
            // if ($lastDocument->document_subtype_id != $document->document_subtype_id || ! empty($request->document_type_id_comment)) {
            //     $history = new DocumentHistory;
            //     $history->document_id = $id;
            //     $history->activity_type = 'Document Sub Type';
            //     $history->previous = DocumentType::where('id', $lastDocument->document_subtype_id)->value('name');
            //     $history->current = DocumentType::where('id', $document->document_subtype_id)->value('name');
            //     $history->comment = $request->document_subtype_id_comment;
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $lastDocument->status;
            //     $history->save();
            // }
            // if ($lastDocument->document_language_id != $document->document_language_id || ! empty($request->document_language_id_comment)) {
            //     $history = new DocumentHistory;
            //     $history->document_id = $id;
            //     $history->activity_type = 'Document Language';
            //     $history->previous = DocumentLanguage::where('id', $lastDocument->document_language_id)->value('name');
            //     $history->current = DocumentLanguage::where('id', $document->document_language_id)->value('name');
            //     $history->comment = $request->document_language_id_comment;
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $lastDocument->status;
            //     $history->save();
            // }
            if ($lastDocument->effective_date != $document->effective_date || ! empty($request->effective_date_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Effective Date';
                $history->previous = $lastDocument->effective_date;
                $history->current = $document->effective_date;
                $history->comment = $request->effective_date_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->next_review_date != $document->next_review_date || ! empty($request->next_review_date_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Next-Review Date';
                $history->previous = $lastDocument->next_review_date;
                $history->current = $document->next_review_date;
                $history->comment = $request->next_review_date_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->review_period != $document->review_period || ! empty($request->review_period_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Review Period';
                $history->previous = $lastDocument->review_period;
                $history->current = $document->review_period;
                $history->comment = $request->review_period_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->attach_draft_doocument != $document->attach_draft_doocument || ! empty($request->attach_draft_doocument_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Draft Document';
                $history->previous = $lastDocument->attach_draft_doocument;
                $history->current = $document->attach_draft_doocument;
                $history->comment = $request->attach_draft_doocument_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->attach_effective_docuement != $document->attach_effective_docuement || ! empty($request->attach_effective_docuement_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Effective Document';
                $history->previous = $lastDocument->attach_effective_docuement;
                $history->current = $document->attach_effective_docuement;
                $history->comment = $request->attach_effective_docuement_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->reviewers != $document->reviewers || ! empty($request->reviewers_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Reviewers';
                $temp = explode(',', $lastDocument->reviewers);
                $revew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRe = User::where('id', $temp[$i])->value('name');
                    array_push($revew, $dataRe);
                }
                $temped = explode(',', $document->reviewers);
                $revewnew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRenew = User::where('id', $temped[$i])->value('name');
                    array_push($revewnew, $dataRenew);
                }

                $history->previous = implode(',', $revew);
                $history->current = implode(',', $revewnew);
                $history->comment = $request->reviewers_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->approvers != $document->approvers || ! empty($request->approvers_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Approvers';
                $temp = explode(',', $lastDocument->approvers);
                $revew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRe = User::where('id', $temp[$i])->value('name');
                    array_push($revew, $dataRe);
                }
                $temped = explode(',', $document->approvers);
                $revewnew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRenew = User::where('id', $temped[$i])->value('name');
                    array_push($revewnew, $dataRenew);
                }

                $history->previous = implode(',', $revew);
                $history->current = implode(',', $revewnew);
                $history->comment = $request->approvers_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->reviewers_group != $document->reviewers_group || ! empty($request->reviewers_group_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Reviewers Group';
                $temp = explode(',', $lastDocument->reviewers_group);
                $revew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRe = Grouppermission::where('id', $temp[$i])->value('name');
                    array_push($revew, $dataRe);
                }
                $temped = explode(',', $document->reviewers_group);
                $revewnew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRenew = Grouppermission::where('id', $temped[$i])->value('name');
                    array_push($revewnew, $dataRenew);
                }

                $history->previous = implode(',', $revew);
                $history->current = implode(',', $revewnew);
                $history->comment = $request->reviewers_group_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->approver_group != $document->approver_group || ! empty($request->approver_group_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Approver Group';
                $temp = explode(',', $lastDocument->approver_group);
                $revew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRe = Grouppermission::where('id', $temp[$i])->value('name');
                    array_push($revew, $dataRe);
                }
                $temped = explode(',', $document->approver_group);
                $revewnew = [];
                for ($i = 0; $i < count($temp); $i++) {
                    $dataRenew = Grouppermission::where('id', $temped[$i])->value('name');
                    array_push($revewnew, $dataRenew);
                }

                $history->previous = implode(',', $revew);
                $history->current = implode(',', $revewnew);
                $history->comment = $request->approver_group_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->revision_summary != $document->revision_summary || ! empty($request->revision_summary_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Revision Summery';
                $history->previous = $lastDocument->revision_summary;
                $history->current = $document->revision_summary;
                $history->comment = $request->revision_summary_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }

            $annexure = Annexure::where('document_id', $id)->first();
            if (! empty($request->serial_number)) {
                $annexure->sno = serialize($request->serial_number);
            }
            if (! empty($request->annexure_number)) {
                $annexure->annexure_no = serialize($request->annexure_number);
            }
            if (! empty($request->annexure_data)) {
                $annexure->annexure_title = serialize($request->annexure_data);
            }
            $annexure->update();

            $documentcontet = DocumentContent::where('document_id', $id)->first();
            $documentcontet->purpose = $request->purpose;
            $documentcontet->scope = $request->scope;
            $documentcontet->procedure = $request->procedure;
            if (! empty($request->responsibility)) {
                $documentcontet->responsibility = serialize($request->responsibility);
            }
            if (! empty($request->abbreviation)) {
                $documentcontet->abbreviation = serialize($request->abbreviation);
            }
            if (! empty($request->defination)) {
                $documentcontet->defination = serialize($request->defination);
            }
            if (! empty($request->reporting)) {
                $documentcontet->reporting = serialize($request->reporting);
            }
            if (! empty($request->materials_and_equipments)) {
                $documentcontet->materials_and_equipments = serialize($request->materials_and_equipments);
            }
            if ($request->hasfile('references')) {

                $image = $request->file('references');

                $ext = $image->getClientOriginalExtension();

                $image_name = date('y-m-d').'-'.rand().'.'.$ext;

                $image->move('upload/document/', $image_name);

                $documentcontet->references = $image_name;
            }

            if (! empty($request->annexuredata)) {
                $documentcontet->annexuredata = serialize($request->annexuredata);
            }

            $documentcontet->update();

            if ($lastContent->purpose != $documentcontet->purpose || ! empty($request->purpose_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Purpose';
                $history->previous = $lastContent->purpose;
                $history->current = $documentcontet->purpose;
                $history->comment = $request->purpose_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->scope != $documentcontet->scope || ! empty($request->scope_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Scope';
                $history->previous = $lastContent->scope;
                $history->current = $documentcontet->scope;
                $history->comment = $request->scope_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->responsibility != $documentcontet->responsibility || ! empty($request->responsibility_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Responsibility';
                $history->previous = $lastContent->responsibility;
                $history->current = $documentcontet->responsibility;
                $history->comment = $request->responsibility_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->abbreviation != $documentcontet->abbreviation || ! empty($request->abbreviation_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Abbreviation';
                $history->previous = $lastContent->abbreviation;
                $history->current = $documentcontet->abbreviation;
                $history->comment = $request->abbreviation_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->defination != $documentcontet->defination || ! empty($request->defination_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Defination';
                $history->previous = $lastContent->defination;
                $history->current = $documentcontet->defination;
                $history->comment = $request->defination_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->materials_and_equipments != $documentcontet->materials_and_equipments || ! empty($request->materials_and_equipments_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Materials and Equipments';
                $history->previous = $lastContent->materials_and_equipments;
                $history->current = $documentcontet->materials_and_equipments;
                $history->comment = $request->materials_and_equipments_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->procedure != $documentcontet->procedure || ! empty($request->procedure_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Procedure';
                $history->previous = $lastContent->procedure;
                $history->current = $documentcontet->procedure;
                $history->comment = $request->procedure_comment;

                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->reporting != $documentcontet->reporting || ! empty($request->reporting_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'Reporting';
                $history->previous = $lastContent->reporting;
                $history->current = $documentcontet->reporting;
                $history->comment = $request->reporting_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastContent->references != $documentcontet->references || ! empty($request->references_comment)) {
                $history = new DocumentHistory;
                $history->document_id = $id;
                $history->activity_type = 'References';
                $history->previous = $lastContent->references;
                $history->current = $documentcontet->references;
                $history->comment = $request->references_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }

            toastr()->success('Document Updated');
            if (Auth::user()->role == 1 || Auth::user()->role == 2) {
                return redirect('rev-details/'.$id);
            } else {
                return redirect('doc-details/'.$id);
            }
        } else {
            toastr()->error('Not working');

            return redirect()->back();
        }

        toastr()->success('Document Updated');

        return redirect()->route('documents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::find($id);
        $document->delete();
        toastr()->success('Deleted successfully');

        return redirect()->back();
    }

    public function createPDF($id)
    {
        $controls = DownloadControl::where('role_id', Auth::user()->role)->first();
        if ($controls) {
            set_time_limit(30);
            $data = Document::find($id);
            $data->department = Department::find($data->department_id);
            $data['originator'] = User::where('id', $data->originator_id)->value('name');
            $time = Carbon::now();
            $data['originator_email'] = User::where('id', $data->originator_id)->value('email');
            $data['document_type_name'] = DocumentType::where('id', $data->document_type_id)->value('name');
            $data['document_type_code'] = DocumentType::where('id', $data->document_type_id)->value('typecode');
            $data['document_division'] = Division::where('id', $data->division_id)->value('name');
            $data['document_content'] = DocumentContent::where('document_id', $id)->first();
            $data['year'] = Carbon::parse($data->created_at)->format('Y');
            // $document = Document::where('id', $id)->get();
            // $pdf = PDF::loadView('frontend.documents.pdfpage', compact('data'))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            $pdf = App::make('dompdf.wrapper');
            $pdf = PDF::loadview('frontend.documents.pdfpage', compact('data', 'time'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();

            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

            $canvas->page_text(
                $width / 3,
                $height / 2,
                $data->status,
                null,
                60,
                [0, 0, 0],
                2,
                6,
                -20
            );

            if ($controls->daily != 0) {
                $user = DownloadHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->where('date', Carbon::now()->format('d-m-Y'))->count();
                if ($user + 1 <= $controls->daily) {
                    //Downlad History
                    $download = new DownloadHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your daily download limit.');

                    return back();
                }
            } elseif ($controls->weekly != 0) {
                $weekDate = Carbon::now()->subDays(7)->format('d-m-Y');
                $user = DownloadHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->weekly) {
                    //Downlad History
                    $download = new DownloadHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your weekly download limit.');

                    return back();
                }
            } elseif ($controls->monthly != 0) {
                $weekDate = Carbon::now()->subDays(30)->format('d-m-Y');
                $user = DownloadHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->monthly) {
                    //Downlad History
                    $download = new DownloadHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your monthly download limit.');

                    return back();
                }
            } elseif ($controls->quatarly != 0) {
                $weekDate = Carbon::now()->subDays(90)->format('d-m-Y');
                $user = DownloadHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->quatarly) {
                    //Downlad History
                    $download = new DownloadHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your quaterly download limit.');

                    return back();
                }
            } elseif ($controls->yearly != 0) {
                $weekDate = Carbon::now()->subDays(365)->format('d-m-Y');
                $user = DownloadHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->yearly) {
                    //Downlad History
                    $download = new DownloadHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your yearly download limit.');

                    return back();
                }
            } else {
                toastr()->error('There is no controls provide for your role.');

                return back();
            }
        } else {
            toastr()->error('There is no controls provide for your role.');

            return back();
        }
    }

    public function viewPdf($id)
    {
        $data = Document::find($id);
        $data->department = Department::find($data->department_id);
        $data['originator'] = User::where('id', $data->originator_id)->value('name');
        $data['originator_email'] = User::where('id', $data->originator_id)->value('email');
        $data['document_type_name'] = DocumentType::where('id', $data->document_type_id)->value('name');
        $data['document_type_code'] = DocumentType::where('id', $data->document_type_id)->value('typecode');

        $data['document_division'] = Division::where('id', $data->division_id)->value('name');
        $data['year'] = Carbon::parse($data->created_at)->format('Y');
        $data['document_content'] = DocumentContent::where('document_id', $id)->first();

        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.documents.pdfpage', compact('data', 'time'))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);
        $pdf->setPaper('A4');
        $pdf->render();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $data->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );

        if ($data->documents) {

            $pdfArray = explode(',', $data->documents);
            foreach ($pdfArray as $pdfFile) {
                $existingPdfPath = public_path('upload/PDF/'.$pdfFile);
                $permissions = 0644; // Example permission value, change it according to your needs
                if (file_exists($existingPdfPath)) {
                    // Create a new Dompdf instance
                    $options = new Options();
                    $options->set('chroot', public_path());
                    $options->set('isPhpEnabled', true);
                    $options->set('isRemoteEnabled', true);
                    $options->set('isHtml5ParserEnabled', true);
                    $options->set('allowedFileExtensions', ['pdf']); // Allow PDF file extension

                    $dompdf = new Dompdf($options);

                    chmod($existingPdfPath, $permissions);

                    // Load the existing PDF file
                    $dompdf->loadHtmlFile($existingPdfPath);

                    // Render the PDF
                    $dompdf->render();

                    // Output the PDF to the browser
                    $dompdf->stream();
                }

            }
        }

        return $pdf->stream('SOP'.$id.'.pdf');
    }

    public function printPDF($id)
    {
        $controls = PrintControl::where('role_id', Auth::user()->role)->first();
        if ($controls) {
            set_time_limit(30);
            $data = Document::find($id);
            $data->department = Department::find($data->department_id);
            $data['originator'] = User::where('id', $data->originator_id)->value('name');
            $data['originator_email'] = User::where('id', $data->originator_id)->value('email');
            $data['document_content'] = DocumentContent::where('document_id', $id)->first();
            $data['document_type_name'] = DocumentType::where('id', $data->document_type_id)->value('name');
            $data['document_type_code'] = DocumentType::where('id', $data->document_type_id)->value('typecode');
            $data['document_division'] = Division::where('id', $data->division_id)->value('name');

            $data['year'] = Carbon::parse($data->created_at)->format('Y');
            // $document = Document::where('id', $id)->get();
            // $pdf = PDF::loadView('frontend.documents.pdfpage', compact('data'))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.documents.pdfpage', compact('data', 'time'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();

            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

            $canvas->page_text(
                $width / 3,
                $height / 2,
                $data->status,
                null,
                60,
                [0, 0, 0],
                2,
                6,
                -20
            );

            if ($controls->daily != 0) {
                $user = PrintHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->where('date', Carbon::now()->format('d-m-Y'))->count();
                if ($user + 1 <= $controls->daily) {
                    //Downlad History
                    $download = new PrintHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->stream('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your daily print limit.');

                    return back();
                }
            } elseif ($controls->weekly != 0) {
                $weekDate = Carbon::now()->subDays(7)->format('d-m-Y');
                $user = PrintHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->weekly) {
                    //Downlad History
                    $download = new PrintHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method
                    return $pdf->stream('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your weekly print limit.');

                    return back();
                }
            } elseif ($controls->monthly != 0) {
                $weekDate = Carbon::now()->subDays(30)->format('d-m-Y');
                $user = PrintHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->monthly) {
                    //Downlad History
                    $download = new PrintHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->download('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your monthly print limit.');

                    return back();
                }
            } elseif ($controls->quatarly != 0) {
                $weekDate = Carbon::now()->subDays(90)->format('d-m-Y');
                $user = PrintHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->quatarly) {
                    //Downlad History
                    $download = new PrintHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->stream('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your quaterly print limit.');

                    return back();
                }
            } elseif ($controls->yearly != 0) {
                $weekDate = Carbon::now()->subDays(365)->format('d-m-Y');
                $user = PrintHistory::where('user_id', Auth::user()->id)->where('document_id', $id)->whereBetween('date', [$weekDate, Carbon::now()->format('d-m-Y')])->count();
                if ($user + 1 <= $controls->yearly) {
                    //Downlad History
                    $download = new PrintHistory;
                    $download->document_id = $id;
                    $download->user_id = Auth::user()->id;
                    $download->role_id = Auth::user()->role;
                    $download->date = Carbon::now()->format('d-m-Y');
                    $download->save();

                    // download PDF file with download method

                    return $pdf->stream('SOP'.$id.'.pdf');
                } else {
                    toastr()->error('You breach your yearly print limit.');

                    return back();
                }
            } else {
                toastr()->error('There is no controls provide for your role.');

                return back();
            }
        } else {
            toastr()->error('There is no controls provide for your role.');

            return back();
        }
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv,pdf'
        // ]);
        if ($request->hasFile('files')) {
            $uploadedFiles = $request->file('files');
            foreach ($uploadedFiles as $uploadedFile) {
                $extension = $uploadedFile->getClientOriginalExtension();
                if ($extension === 'pdf') {
                    // Process PDF file
                    $originalName = $uploadedFile->getClientOriginalName();
                    $destinationPath = public_path('upload/PDF');

                    if (! file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    // $file = $request->file('file');
                    $storedFileName = $uploadedFile->storeAs('upload/PDF', $originalName);

                    toastr()->success('PDF file uploaded successfully!');
                } elseif ($extension === 'csv' || $extension === 'xls' || $extension === 'xlsx') {
                    $import = new DocumentsImport();
                    Excel::import($import, $uploadedFile);

                    toastr()->success('CSV file imported successfully!');
                } else {
                    toastr()->error('Invalid file format. Only PDF and CSV files are allowed.');
                }
            }

            return back();
        }

        toastr()->error('No files uploaded!');

        return back();
    }

    public function revision(Request $request, $id)
    {
        // return $id;

        $document = Document::find($id);
        $document->revision = 'Yes';
        $document->revision_policy = $request->revision;
        $document->update();
        $newdoc = new Document();
        $newdoc->originator_id = $document->originator_id;
        $newdoc->division_id = $document->division_id;
        $newdoc->process_id = $document->process_id;
        $newdoc->revised = 'Yes';
        $newdoc->revised_doc = $document->id;
        $newdoc->document_name = $document->document_name;
        $newdoc->short_description = $document->short_description;
        $newdoc->due_dateDoc = $document->due_dateDoc;
        $newdoc->description = $document->description;
        //$newdoc->notify_to = $document->notify_to;
        $newdoc->reference_record = $document->reference_record;
        $newdoc->department_id = $document->department_id;
        $newdoc->document_type_id = $document->document_type_id;
        $newdoc->document_subtype_id = $document->document_subtype_id;
        $newdoc->document_language_id = $document->document_language_id;
        $newdoc->keywords = $document->keywords;
        $newdoc->effective_date = $document->effective_date;
        $newdoc->next_review_date = $document->next_review_date;
        $newdoc->review_period = $document->review_period;
        $newdoc->attach_draft_doocument = $document->attach_draft_doocument;
        $newdoc->attach_effective_docuement = $document->attach_effective_docuement;
        $newdoc->approvers = $document->approvers;
        $newdoc->reviewers = $document->reviewers;
        $newdoc->approver_group = $document->approver_group;
        $newdoc->reviewers_group = $document->reviewers_group;
        $newdoc->revision_summary = $document->revision_summary;
        $newdoc->training_required = $document->training_required;
        $newdoc->trainer = $request->trainer;
        $newdoc->comments = $request->comments;
        //$newdoc->purpose = $request->purpose;
        $newdoc->stage = 1;
        $newdoc->status = Stage::where('id', 1)->value('name');
        $newdoc->save();

        $doc_content = new DocumentContent();
        $doc_content->document_id = $newdoc->id;
        $doc_content->purpose = $doc_content->purpose;
        $doc_content->scope = $doc_content->scope;
        $doc_content->responsibility = $doc_content->responsibility;
        $doc_content->abbreviation = $doc_content->abbreviation;
        $doc_content->defination = $doc_content->defination;
        $doc_content->materials_and_equipments = $doc_content->materials_and_equipments;
        $doc_content->procedure = $doc_content->procedure;
        $doc_content->reporting = $doc_content->reporting;
        $doc_content->references = $doc_content->references;
        $doc_content->save();

        if ($document->training_required == 'yes') {
            $docTrain = DocumentTraining::where('document_id', $document->id)->first();
            if (! empty($docTrain)) {
                $trainning = new DocumentTraining();
                $trainning->document_id = $newdoc->id;
                $trainning->trainer = $docTrain->trainer;
                $trainning->cbt = $docTrain->cbt;
                $trainning->type = $docTrain->type;
                $trainning->comments = $docTrain->comments;
                $trainning->save();
            }

        }

        $annexure = Annexure::where('document_id', $id)->first();
        $new_annexure = new Annexure();
        $new_annexure->document_id = $newdoc->id;
        $new_annexure->sno = $annexure->sno;
        $new_annexure->annexure_no = $annexure->annexure_no;
        $new_annexure->annexure_title = $annexure->annexure_title;
        $new_annexure->save();

        toastr()->success('Document is revised, you can change the body!!');
        return redirect()->route('documents.edit', $newdoc->id);
    }
}
