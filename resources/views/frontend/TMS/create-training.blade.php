@extends('frontend.layout.main')
@section('container')
    @include('frontend.TMS.head')

    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
        @php
        toastr()->error($error);
        @endphp
        @endforeach
    @endif  
    {{-- ======================================
                CREATING TRAINING
    ======================================= --}}
    <div id="create-training-plan">
        <div class="container-fluid">

            <form action="{{ route('TMS.store') }}" method="POST">
                @csrf
                <div class="inner-block">
                    <div class="main-head">
                        Basic Information
                    </div>
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="training-name">Training Plan Name</label>
                                    <input type="text" id="traning_plan_name" name="traning_plan_name" required>
                                </div>
                                <p id="trainingPlan" style="color: red">
                                    ** Training plan is missing...
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="training-id">Training Plan ID</label>
                                    <div class="static">Not-Applicable</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="training-type">Training Plan Type</label>
                                    <select id="training-select" name="training_plan_type" required onchange="toggleMultiSelect()">
                                        <option value="">---</option>
                                        <option value="Read & Understand">Read & Understand</option>
                                        <option value="Read & Understand with Questions">Read & Understand with Questions
                                        </option>
                                        <option value="Classroom Training">Classroom Training</option>
                                    </select>
                                    <p id="trainingType" style="color: red">
                                        ** Training type is missing...
                                    </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input" id="assessmentBlock" style="display: none">
                                    <label for="classRoom_trainingName">Assessment Required? <span class="text-danger">*</span></label>
                                    <select class="assessment_required" id="assessment_required" name="assessment_required" placeholder="SelectclassRoom_training Name">
                                        <option value="">-- Select --</option>
                                        <option value="yes"> Yes</option>
                                        <option value="no"> No</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="group-input" id="classroomTrainingBlock" style="display: none">
                                    <label for="classRoom_trainingName">Class Room Trainer <span class="text-danger">*</span></label>
                                    <select multiple class="classRoom_training" name="classRoom_training[]" placeholder="Select class room trainer" style="border: 1px solid #ddd"
                                        data-search="false" data-silent-initial-value-set="true" id="classRoom_training">
                                        @foreach ($traineesPerson as $user_id)
                                            @php
                                                $user = \App\Models\User::find($user_id);
                                            @endphp
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var selectField = document.getElementById('training-select');
                                    var inputsToToggle = [];

                                    // Add elements with class 'facility-name' to inputsToToggle
                                    var facilityNameInputs = document.getElementsByClassName('assessment_required');
                                    var facilityNameInputs1 = document.getElementsByClassName('classRoom_training');
                                    
                                    for (var i = 0; i < facilityNameInputs.length; i++) {
                                        inputsToToggle.push(facilityNameInputs[i]);
                                    }
                                    for (var i = 0; i < facilityNameInputs.length; i++) {
                                        inputsToToggle.push(facilityNameInputs1[i]);
                                    }
                                                                    
                                    selectField.addEventListener('change', function () {
                                        var isRequired = this.value === 'Classroom Training';

                                        inputsToToggle.forEach(function (input) {
                                            input.required = isRequired;
                                        });
                                    });
                                });
                            </script>

                            <script>
                                function toggleMultiSelect() {
                                  var selectedValue = document.getElementById("training-select").value;
                                  var multiSelectField = document.getElementById("classroomTrainingBlock");
                                  var multiSelectField1 = document.getElementById("assessmentBlock");
                                
                                  if (selectedValue === "Classroom Training") {
                                    multiSelectField.style.display = "block";
                                    multiSelectField1.style.display = "block";
                                  } else {
                                    multiSelectField.style.display = "none";
                                    multiSelectField1.style.display = "none";
                                  }
                                }
                                </script>
                                 {{-- <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var selectField = document.getElementById('training-select');
                                        var inputsToToggle = [];

                                        // Add elements with class 'facility-name' to inputsToToggle
                                        var facilityNameInputs = document.getElementsByClassName('assessment_required');
                                        
                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                            inputsToToggle.push(facilityNameInputs[i]);
                                        }

                                                                        
                                        selectField.addEventListener('change', function () {
                                            var isRequired = this.value === 'Classroom Training';

                                            inputsToToggle.forEach(function (input) {
                                                input.required = isRequired;
                                            });

                                            // Show or hide the asterisk icon based on the selected value
                                            var asteriskIcon = document.getElementById('assessmentrequiredAstrik');
                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                        });
                                    });
                                </script> --}}
                            {{-- <div class="col-6">
                                <div class="group-input">
                                    <label for="classRoom_trainingName">Training Start Date & Time </label>
                                    <input type="datetime-local" name="training_start_date">
                                </div>
                            </div> --}}
                            {{-- <div class="col-6">
                                <div class="group-input">
                                    <label for="classRoom_trainingName">Training End  Date & Time</label>
                                    <input type="datetime-local" name="training_end_date">
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="desc">Training Plan Description</label>
                                    <textarea name="desc"></textarea>
                                </div>
                            </div>

                             
                            <script>
                                $(document).ready(function () {
                                    $(".add_training_attachment").click(function(){
                                        $("#myfile").trigger("click");
                                    });
                                });

                                function addAttachmentFiles(input, block_id) {
                                    console.log('test')
                                        let block = document.getElementById(block_id);
                                        let files = input.files;
                                        for (let i = 0; i < files.length; i++) {
                                            let div = document.createElement('div');
                                            div.className = 'attachment-item'; 
                                            div.innerHTML = files[i].name;
                            
                                            let viewLink = document.createElement("a");
                                            viewLink.href = URL.createObjectURL(files[i]);
                                            viewLink.textContent = "</View>";
                                            viewLink.addEventListener('click', function(e){
                                                e.preventDefault();
                                                window.open(viewLink.href,'_blank');
                                            });
                            
                                          
                                            let removeButton = document.createElement("a");
                                            removeButton.className = 'remove-button';
                                            removeButton.textContent = "</Remove>";
                                            removeButton.addEventListener('click', function() {
                                                div.remove();
                                                input.value = ''; 
                                            });

                                            console.log(removeButton)
                            
                                            div.appendChild(viewLink);
                                            div.appendChild(removeButton);
                                            block.appendChild(div);
                                        }
                                    }
                                    
                            </script>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="attachments">Attachments</label>
                                    <div><small class="text-primary">Please attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="training_attachment"></div>
                                        <div class="add-btn">
                                            <div class="add_training_attachment" style="cursor: pointer;">Add</div>
                                            <input type="file" id="myfile" name="training_attachment[]" oninput="addAttachmentFiles(this, 'training_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-12" >
                                <div class="group-input">
                                    <label for="quize">Quizz <span id="quizz" class="text-danger">*</span></label>
                                    <select id="quizzz" name="quize">
                                        <option value="">---</option>
                                        @foreach ($quize as $temp)
                                            <option data-id="{{ $temp->passing }}" value="{{ $temp->id }}">
                                                {{ $temp->title }}</option>
                                        @endforeach
                                    </select>
                                    <p id="trainingQuiz" style="color: red">
                                        ** Training quiz is missing...
                                    </p>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#quizz').hide();
                            
                                    $('[name="classRoom_training[]"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('#quizz').show();
                                            $('#quizz span').show();
                                        } else {
                                            $('#quizz').hide();
                                            $('#quizz span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="effective-criteria">Effective Criteria(in %)</label>
                                    <input type="number" name="effective_criteria" required>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6" id="trainee-criteria">
                                <div class="group-input">
                                    <label for="trainee-criteria">Trainee Criteria(in %)</label>
                                    <input type="number" id="effective" name="trainee_criteria">
                                </div>
                                <p id="trainingCriteria" style="color: red">
                                    ** Trainee Criteria is missing...
                                </p>
                            </div> --}}
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for=""></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="inner-block">
                            <div class="main-head">
                                Selecting SOP's
                            </div>
                            <div class="inner-block-content">
                                <div class="search-bar">
                                    <input type="text" name="search" placeholder="Search SOP's">
                                    <label for="search"><i class="fa-solid fa-magnifying-glass"></i></label>
                                </div>
                                <div class="selection-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Document Number</th>
                                                <th>Document Title</th>
                                                <th>Originator</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($due as $temp)
                                                @if ($temp->root_document)
                                                    @if ($temp->root_document->stage >= 6 && $temp->trainer == auth()->id() && $temp->root_document->status == 'Under-Training' && $temp->status == 'Past-due')
                                                        <tr>
                                                            <td class="text-center">
                                                                <input 
                                                                class="select-sop-js"
                                                                data-id="{{ $temp->root_document->id }}"
                                                                type="checkbox" id="sopData" name="sops[]" value="{{ $temp->document_id }}">
                                                            </td>

                                                                    @php
                                                                    $temp1 = DB::table('document_types')
                                                                        ->where('name', $temp->document_type_name)
                                                                        ->value('typecode');
                                                                    @endphp        
                                                            <td>{{ $temp->division_name }}/@if($temp->document_type_name){{  $temp1 }} /@endif{{$temp->year}}/
                                                                000{{ $temp->root_document ? $temp->root_document->document_number : '' }}/R{{ $temp->major}}.{{$temp->minor}}</td>
                                                            <td>
                                                                {{ $temp->root_document ? $temp->root_document->document_name : '' }}
                                                            </td>
                                                            <td>{{ $temp->originator }}</td>
                                                        </tr>
                                                    @elseif($temp->root_document->status == 'Effective')
                                                        <tr>
                                                            <td class="text-center">
                                                                <input 
                                                                class="select-sop-js"
                                                                data-id="{{ $temp->root_document->id }}"
                                                                type="checkbox" id="sopData" name="sops[]" value="{{ $temp->document_id }}">
                                                            </td>

                                                                    @php
                                                                    $temp1 = DB::table('document_types')
                                                                        ->where('name', $temp->document_type_name)
                                                                        ->value('typecode');
                                                                    @endphp        
                                                            <td>{{ $temp->division_name }}/@if($temp->document_type_name){{  $temp1 }} /@endif{{$temp->year}}/
                                                                000{{ $temp->root_document ? $temp->root_document->document_number : '' }}/R{{ $temp->major}}.{{$temp->minor}}</td>
                                                            <td>
                                                                {{ $temp->root_document ? $temp->root_document->document_name : '' }}
                                                            </td>
                                                            <td>{{ $temp->originator }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <p id="SOPType" style="color: red">
                                        ** Please Select atliest one SOP...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="inner-block">
                            <div class="main-head">
                                Selecting Trainees
                            </div>
                            <div class="inner-block-content">
                                <div class="search-bar">
                                    <input type="text" name="search" placeholder="Search Trainees">
                                    <label for="search"><i class="fa-solid fa-magnifying-glass"></i></label>
                                </div>
                                <div class="text-center" style="display: none" id="fetchUserBlock">
                                    fetching users...
                                </div>
                                <div class="selection-table user-selection-table-js">
                                    @include('frontend.TMS.comps.training-users')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="inner-block add-question" id="question-select">
                    <div class="main-head">
                        Select Questions for Trainees
                    </div>
                    <div class="inner-block-content">
                        <div class="question-container">
                            <div class="left-block">
                                <div class="head">Select Questions</div>
                                <div class="table-max">
                                    <table class="table table-bordered left-table">
                                        <thead>
                                            <tr>
                                                <th>Question</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody id="training-question">
                                            <tr data-item="item1">
                                                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est nesciunt at
                                                    cupiditate.</td>
                                                <td>Single Select Questions</td>
                                            </tr>
                                            <tr data-item="item2">
                                                <td>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae dolor
                                                    reiciendis
                                                    ullam.</td>
                                                <td>Multi Selection Questions</td>
                                            </tr>
                                            <tr data-item="item3">
                                                <td>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae dolor
                                                    reiciendis
                                                    ullam.</td>
                                                <td>Exact Answer Questions</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="head">Selected Questions</div>
                                <div class="table-max">
                                    <table class="table table-bordered right-table">
                                        <thead>
                                            <tr>
                                                <th>Question</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="training-ques-selected">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="foot-btns">
                    <a href="{{ route('TMS.index') }}"><button>Cancel</button></a>
                    <button type="submit" id="SubmitTraining">Create</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        $('input[name="sops[]"]').change(async function() {

            let getUrl = "{{ route('sop_training_users') }}";
            const isChecked = $(this).is(':checked');

            if (isChecked) {
                let sopId = $(this).data('id');
                getUrl = `${getUrl}/${sopId}`;
             
                $('#fetchUserBlock').show();

                try {
                    
                    const res = await axios.get(getUrl);

                    if (res.data.status == 'ok') {

                        $('.user-selection-table-js').html(res.data.body);

                    } else {
                        throw Error(res.data.message);
                    }

                } catch (err) {
                    console.log('Error in sop user fetching', err.message);
                }

                $('#fetchUserBlock').hide();

            }
        })

    </script>

    <script>
        $(document).ready(function() {
            $("#training-select").change(function() {
                var selectedValue = $(this).val();
                var quizDiv = $("#quizz");
                var traineeCriteriaDiv = $("#trainee_criteria");
                if (selectedValue === "Read & Understand") {
                    quizDiv.hide();
                    traineeCriteriaDiv.hide();
                } else if (selectedValue === "Read & Understand with Questions") {
                    quizDiv.show();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "Classroom Training") {
                    quizDiv.hide();
                    traineeCriteriaDiv.show();
                }
            });
        });

        const itemList = document.getElementById('training-question');
        const selectedList = document.getElementById('training-ques-selected');
        itemList.addEventListener('click', function(e) {
            const selectedItem = e.target.closest('tr');
            if (selectedItem) {
                const itemData = selectedItem.getAttribute('data-item');
                const existingItem = selectedList.querySelector(`tr[data-item="${itemData}"]`);
                if (!existingItem) {
                    const newItem = selectedItem.cloneNode(true);
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.addEventListener('click', function() {
                        newItem.remove();
                    });
                    const td = document.createElement('td');
                    td.appendChild(deleteBtn);
                    newItem.appendChild(td);
                    selectedList.appendChild(newItem);
                }
            }
        });
    </script>
      <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#capa_related_record ,#classRoom_training' 
        });
    </script>
@endsection
