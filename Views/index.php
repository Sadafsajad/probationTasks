<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" />

    <style>
        .color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .pR0 {
            padding-right: 0% !important;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 0 auto;
            /* padding: 20px; */
            border: 1px solid #888;
            max-width: 100vw;
            /* Set max-width to 100% of the viewport width */
        }

        .modal-header .close {
            margin: -1rem -1rem -1rem auto;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group label i {
            margin-right: 5px;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 1000px;
                margin: 1.75rem auto;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    <?php foreach ($checklists as $checklist): ?>
                                    {
                            title: '<?php echo $checklist['title']; ?>',
                            start: '<?php echo $checklist['date_from']; ?>',
                            end: '<?php echo $checklist['date_to']; ?>',
                            color: '<?php echo $checklist['priority']; ?>',
                            checklist: '<?php echo $checklist['id']; ?>',
                        },
                    <?php endforeach; ?>
                        <?php foreach ($leaves as $leave): ?>
                                    {
                            title: '<?php echo $leave['title']; ?>',
                            start: '<?php echo $leave['start_date']; ?>',
                            end: '<?php echo $leave['end_date']; ?>',
                            color: 'red',
                            checklist: false
                        },
                    <?php endforeach; ?>
                ],
                eventClick: function (event) {
                    console.log(event.event.extendedProps);
                    if (event.event.extendedProps.checklist) {
                        viewSubCheckLists(event.event.extendedProps.checklist);
                    }
                },
                dateClick: function (info) {
                    alert("Hi, you clicked on " + info.dateStr);
                },
            });
            calendar.render();
        });
    </script>
</head>

<body>
    <div class="row">
        <div class="col-xl-4 col-xxl-4 col-lg-4 col-sm-4 pR0">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-primary btn-sm mr-2" id="addChecklistBtn">Add Checklist</button>
                        <button class="btn btn-primary btn-sm mr-2" id="addLeaveModal">Add Leave</button>
                        <button class="btn btn-danger btn-sm" id="deleteAll">Clear All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Search</h5>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <h5>Priority</h5>
                            <div class="d-flex ml-3">
                                <div class="color-box bg-success" title="Low"></div>
                                <div class="color-box bg-warning ml-2" title="Medium"></div>
                                <div class="color-box bg-danger ml-2" title="High"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <?php foreach ($checklists as $checklist): ?>
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <?php echo $checklist['title']; ?>
                                    <div class="color-box bg-medium ml-2 bg-success" title="Medium"></div>
                                </div>
                                <div>
                                    <!-- <button class="btn btn-primary btn-sm mr-2">Edit</button>
                        <button class="btn btn-danger btn-sm">Delete</button> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php
                                    // Fetch subchecklists for the current checklist entry
                                    $stmt = $pdo->prepare("SELECT * FROM sub_checklists WHERE checklist_id = :checklistId");
                                    $stmt->bindParam(':checklistId', $checklist['id']);
                                    $stmt->execute();
                                    $subChecklists = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($subChecklists as $subChecklist):
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo $subChecklist['title']; ?>
                                            <div>
                                                <!-- <button class="btn btn-primary btn-sm mr-2"
                                            >Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button> -->
                                                <i class="fas text-primary fa-edit mr-2 editSubChecklistModalBtn"
                                                    data-subchecklist-id="<?php echo $subChecklist['id']; ?>"
                                                    id="editSubChecklistModalBtn"
                                                    onclick="editSubCheckLists(<?php echo $subChecklist['id']; ?>)"></i>
                                                <i class="fas text-danger fa-trash mr-2"
                                                    onclick="return confirm('Are you sure you want to delete this?');"
                                                    onclick="deleteSubCheckLists(<?php echo $subChecklist['id']; ?>)"></i>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Checkbox card HTML structure -->
        </div>
        <div class="col-xl-8 col-xxl-8 col-lg-8 col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="checklistModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" id="dismissChecklistModalIcon"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="addData.php" method="post">
                        <input type="hidden" name="addChecklist" value="true">
                        <div class="form-group">
                            <label for="eventTitle"><i class="fas fa-calendar-alt"></i> Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" name="eventTitle"
                                placeholder="Event title" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="startDate"><i class="fas fa-calendar-alt"></i> Start Date/Time</label>
                                <input type="date" class="form-control" id="startDate" name="startDate"
                                    value="2024-05-20">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="endDate"><i class="fas fa-calendar-alt"></i> End Date/Time</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" value="2024-05-20">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="repeatOption"><i class="fas fa-sync"></i> Repeat</label>
                            <select class="form-control" id="repeatOption" name="repeatOption">
                                <option value="1">Repeat</option>
                                <option value="0">No Repeat</option>
                                <!-- Add more options for repeat frequency -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="fas fa-file-alt"></i> Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="status"><i class="fas fa-check-circle"></i> Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">active</option>
                                    <option value="inActive">inActive</option>
                                    <!-- Add more options for status -->
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="priority"><i class="fas fa-cog"></i> Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="#28a745">Low</option>
                                    <option value="#ffc107">Medium</option>
                                    <option value="#dc3545">hard</option>
                                    <!-- Add more options for priority -->
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="category"><i class="fas fa-folder"></i> Category</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="c1">Category 1</option>
                                    <option value="c2">Category 2</option>
                                    <!-- Add more options for category -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment"><i class="fas fa-comment"></i> Comment</label>
                            <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="accessLevel"><i class="fas fa-lock"></i> Access Level</label>
                                <select class="form-control" id="accessLevel" name="accessLevel">
                                    <option value="0">None</option>
                                    <option value="1">Read Only</option>
                                    <!-- Add more options for access level -->
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="colorPicker"><i class="fas fa-palette"></i> Color</label>
                                <input type="color" class="form-control" id="colorPicker" value="#ffffff"
                                    name="colorPicker">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="subChecklist"><i class="fas fa-paper-plane"></i> Add Subchecklist</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="subChecklist"
                                        name="subChecklist">
                                    <label class="custom-control-label" for="subChecklist"></label>
                                </div>
                            </div>
                        </div>
                        <div id="additionalFieldsContainer" style="display: none;">
                            <!-- Additional fields -->
                            <div class="form-row">
                                <!-- Additional field 1 -->
                                <div class="form-group col-md-6">
                                    <label for="subChecklistname"> Name</label>
                                    <input type="text" class="form-control" id="subChecklistname"
                                        name="subChecklistname">
                                </div>
                                <!-- Additional field 2 -->
                                <div class="form-group col-md-6">
                                    <label for="subChecklistdate"><i class="fas fa-calendar-alt"></i> Date</label>
                                    <input type="date" class="form-control" id="subChecklistdate"
                                        name="subChecklistdate" value="2024-05-20">
                                </div>
                            </div>
                            <!-- Additional fields continued -->
                            <!-- Additional field 3 -->
                            <div class="form-group">
                                <label for="subChecklistDescription"><i class="fas fa-file-alt"></i> Description</label>
                                <textarea class="form-control" id="subChecklistDescription"
                                    name="subChecklistDescription" rows="3"></textarea>

                            </div>
                            <!-- Additional field 4 -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="subChecklistStatus"><i class="fas fa-check-circle"></i> Status</label>
                                    <input type="text" class="form-control" id="subChecklistStatus"
                                        name="subChecklistStatus">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="subChecklistPriority"><i class="fas fa-cog"></i> Priority</label>
                                    <select class="form-control" id="subChecklistPriority" name="subChecklistPriority">
                                        <option value="#28a745">Low</option>
                                        <option value="#ffc107">Medium</option>
                                        <option value="#dc3545">hard</option>
                                        <!-- Add more options for priority -->
                                    </select>
                                </div>
                            </div>
                            <!-- Additional field 5 -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="subChecklistCategory"><i class="fas fa-folder"></i> Category</label>
                                    <select class="form-control" id="subChecklistCategory" name="subChecklistCategory">
                                        <option value="c1">Category 1</option>
                                        <option value="c2">Category 2</option>
                                        <!-- Add more options for category -->
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="subChecklistComment"><i class="fas fa-comment"></i> Comment</label>
                                    <textarea class="form-control" id="subChecklistComment" rows="3"
                                        name="subChecklistComment"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="dismissChecklistModal"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="leaveModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" id="dismissLeaveModalIcon"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="addData.php" method="post">
                        <input type="hidden" name="addLeave" value="true">
                        <div class="form-group">
                            <label for="eventTitle"><i class="fas fa-calendar-alt"></i> Event Title</label>
                            <input type="text" class="form-control" id="leaveTitle" placeholder="Event title"
                                name="leaveTitle">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="startDate"><i class="fas fa-calendar-alt"></i> Start Date/Time</label>
                                <input type="date" class="form-control" id="leaveStartDate" name="leaveStartDate"
                                    value="2024-05-20">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="endDate"><i class="fas fa-calendar-alt"></i> End Date/Time</label>
                                <input type="date" class="form-control" id="leaveEndDate" name="leaveEndDate"
                                    value="2024-05-20">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="category"><i class="fas fa-folder"></i> Category</label>
                            <select class="form-control" id="leaveCategory" name="leaveCategory">
                                <option value="plan">Plan</option>
                                <option value="unplan">Un Plan</option>
                                <!-- Add more options for category -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="dismissLeaveModal"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="editSubChecklistModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" id="dismissEditSubChecklistModalIcon"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name"><i class="fas fa-calendar-alt"></i> Name</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endDate"><i class="fas fa-calendar-alt"></i> Date</label>
                            <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="description"><i class="fas fa-file-alt"></i> Description</label>
                            <input type="text" class="form-control" id="description">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status"><i class="fas fa-check-circle"></i> Status</label>
                            <input type="text" class="form-control" id="status">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="priority"><i class="fas fa-cog"></i> Priority</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="#28a745">Low</option>
                                <option value="#ffc107">Medium</option>
                                <option value="#dc3545">hard</option>
                                <!-- Add more options for priority -->
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="category"><i class="fas fa-folder"></i> Category</label>
                            <select class="form-control" id="category" name="category">
                                <option value="c1">Category 1</option>
                                <option value="c2">Category 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment"><i class="fas fa-comment"></i> Comment</label>
                        <textarea class="form-control" id="4" rows="3" id="comment"></textarea>
                    </div>
                    <input type="hidden" name="subchecklist-id" id="subchecklist-id" value="">
                    <button type="button" class="btn btn-primary" id="updateSubchecklistBtn">Save</button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="dismissEditSubChecklistModal" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="showSubcheckLists">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sub CheckLists Of This Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" id="dismissShowSubcheckListsIcon"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventTitle"><i class="fas fa-calendar-alt"></i> Event Title</label>
                        <input type="text" class="form-control" id="name" placeholder="Event title" name="name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="dismissShowSubcheckLists"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- <script src="script.js"></script> -->
<script>
    $(document).ready(function () {
        $('#addChecklistBtn').click(function () {
            $('#checklistModal').show();
        });
        $('#addLeaveModal').click(function () {
            $('#leaveModal').show();
        });
        // Function to dismiss modals when the close button is clicked
        function dismissModal(modalId) {
            $('#' + modalId).hide();
        }

        // Event listeners for close buttons
        $('#dismissChecklistModalIcon').click(function () {
            dismissModal('checklistModal');
        });
        $('#dismissShowSubcheckListsIcon').click(function () {
            dismissModal('showSubcheckLists');
        });

        $('#dismissLeaveModalIcon').click(function () {
            dismissModal('leaveModal');
        });
        $('#dismissEditSubChecklistModalIcon').click(function () {
            dismissModal('editSubChecklistModal');
        });

        // Event listeners for footer close buttons
        $('#dismissChecklistModal').click(function () {
            dismissModal('checklistModal');
        });

        $('#dismissLeaveModal').click(function () {
            dismissModal('leaveModal');
        });
        $('#dismissEditSubChecklistModal').click(function () {
            dismissModal('editSubChecklistModal');
        });
        $('#dismissShowSubcheckLists').click(function () {
            dismissModal('showSubcheckLists');
        });
        $('#updateSubchecklistBtn').click(function () {
            var subchecklistId = $('#editSubChecklistModal #subchecklist-id').val();
            var name = $('#editSubChecklistModal #name').val();
            var startDate = $('#editSubChecklistModal #startDate').val();
            var description = $('#editSubChecklistModal #description').val();
            var status = $('#editSubChecklistModal #status').val();
            var priority = $('#editSubChecklistModal #priority').val();
            var category = $('#editSubChecklistModal #category').val();
            var comment = $('#editSubChecklistModal #comment').val();
            // Get other form data similarly
            console.log(subchecklistId)
            // Perform AJAX request to update subchecklist
            $.ajax({
                url: 'alterData.php',
                method: 'POST',
                data: {
                    action: 'updateSubchecklist',
                    subchecklist_id: subchecklistId,
                    name: name,
                    startDate: startDate,
                    description: description,
                    status: status,
                    priority: priority,
                    category: category,
                    comment: comment
                    // Pass other form data as needed
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response)
                    location.reload();
                    // Handle success response
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#deleteAll').click(function () {
            var subchecklistId = $(this).data('subchecklist-id');

            // Confirm deletion

            // Perform AJAX request to delete the sublist
            var confirmed = confirm('Are you sure you want to delete everything?');
            if (confirmed) {
                $.ajax({
                    url: 'alterData.php', // Update with your server-side script
                    method: 'POST',
                    data: {
                        action: 'deleteAllData',
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Handle success response
                        console.log(response);
                        location.reload();
                        // Reload or update the sublist display as needed
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        // Handle error
                    }
                });
            }

        });
        $('#subChecklist').change(function () {
            if ($(this).is(':checked')) {
                $('#additionalFieldsContainer').show();
            } else {
                $('#additionalFieldsContainer').hide();
            }
        });
    });
    function editSubCheckLists(subchecklistId) {
        // var subchecklistId = $(this).data('subchecklist-id');
        // alert(subchecklistId);

        // Perform AJAX request to fetch subchecklist data by ID
        $.ajax({
            url: 'alterData.php',
            method: 'POST',
            data: {
                action: 'fetchSubchecklistData',
                subchecklist_id: subchecklistId,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response)
                // Populate modal fields with subchecklist data
                $('#editSubChecklistModal #name').val(response.title);
                $('#editSubChecklistModal #startDate').val(response.sub_checklist_date);
                $('#editSubChecklistModal #description').val(response.description);
                $('#editSubChecklistModal #status').val(response.status);
                $('#editSubChecklistModal #comment').val(response.comment);
                $('#editSubChecklistModal #subchecklist-id').val(response.id);
                var selectedPriority = response.priority;
                $('#editSubChecklistModal').find('[name="priority"]').val(selectedPriority).trigger('change');
                var selectedCategory = response.category;
                $('#editSubChecklistModal').find('[name="category"]').val(selectedCategory).trigger('change');
                // Populate other fields similarly
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        // Show the modal
        $('#editSubChecklistModal').show();
    };
    function deleteSubCheckLists(subchecklistId) {
        // var subchecklistId = $(this).data('subchecklist-id');
        // alert(subchecklistId);

        // Perform AJAX request to fetch subchecklist data by ID
        $.ajax({
            url: 'alterData.php',
            method: 'POST',
            data: {
                action: 'deleteSubchecklist',
                subchecklist_id: subchecklistId,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response)
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    };
    function viewSubCheckLists(checklistId) {
        // var subchecklistId = $(this).data('subchecklist-id');
        // alert(subchecklistId);

        // Perform AJAX request to fetch subchecklist data by ID
        $.ajax({
            url: 'alterData.php',
            method: 'POST',
            data: {
                action: 'viewSubchecklist',
                checklistId: checklistId,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('#showSubcheckLists #name').val(response[0].title);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        $('#showSubcheckLists').show();
    };
</script>