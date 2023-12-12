@extends('layouts.admin')
@section('title', 'POS')
@section('content')
<div id="content" class="main-content w-100 mt-2">
    <div class="row">
        <div class="col-12">

            <div class="mail-box-container">
                <div class="mail-overlay"></div>

                <div class="tab-title">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-clipboard">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2">
                                </path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <h5 class="app-title">Todo List</h5>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12 ps-0">
                            <div class="todoList-sidebar-scroll mt-4">
                                <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link list-actions active" id="all-list" data-toggle="pill"
                                            href="#pills-inbox" role="tab" aria-selected="true"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-list">
                                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                                <line x1="3" y1="6" x2="3" y2="6"></line>
                                                <line x1="3" y1="12" x2="3" y2="12"></line>
                                                <line x1="3" y1="18" x2="3" y2="18"></line>
                                            </svg> Inbox <span class="todo-badge badge"></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link list-actions" id="todo-task-done" data-toggle="pill"
                                            href="#pills-sentmail" role="tab" aria-selected="false"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-thumbs-up">
                                                <path
                                                    d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                                                </path>
                                            </svg> Done <span class="todo-badge badge"></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link list-actions" id="todo-task-important" data-toggle="pill"
                                            href="#pills-important" role="tab" aria-selected="false"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-star">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg> Important <span class="todo-badge badge"></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link list-actions" id="todo-task-trash" data-toggle="pill"
                                            href="#pills-trash" role="tab" aria-selected="false"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-trash-2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg> Trash</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12">
                            <button class="btn btn-secondary" id="addTask" onclick="showModal()"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-plus">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg> New Task</button>
                        </div>
                    </div>
                </div>

                <div id="todo-inbox" class="accordion todo-inbox">
                    <div class="search">
                        <input type="text" class="form-control input-search" placeholder="Search Task...">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-menu mail-menu d-lg-none">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </div>

                    <div class="todo-box">

                        <div id="ct" class="todo-box-scroll">
                            @foreach ($todos as $todo)
                            <div class="todo-item all-list {{ $todo->status == 'important' ? 'todo-task-important' : ''}} {{ $todo->status == 'done' ? 'todo-task-done' : ''}}">
                                <div class="todo-item-inner">
                                    <div class="n-chk text-center">
                                        <div class="form-check form-check-primary form-check-inline mt-1 me-0"
                                            data-bs-toggle="collapse" data-bs-target>
                                            <input class="form-check-input inbox-chkbox" onclick="markDone({{ $todo->id }})" {{ $todo->status == 'done' ? 'checked' : ''}} type="checkbox">
                                        </div>
                                    </div>

                                    <div class="todo-content" style="width:77%;">
                                        <h5 class="todo-heading" data-todoHeading="{{ $todo->title }}">
                                            {{ $todo->title }} </h5>
                                        <p class="todo-text"
                                            data-todoHtml="<p>{{ $todo->notes }}</p>"
                                            data-todoText='[{"insert":"{{ $todo->notes }}"}]'
                                            data-due='[{"date":"{{ $todo->due }}"}]'
                                            data-id='[{"id":"{{ $todo->id }}"}]'>
                                            {{ $todo->notes }}</p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-danger">Due: {{ date("d M Y", strtotime($todo->due)) }}</span>
                                    </div>

                                    <div class="priority-dropdown custom-dropdown-icon">

                                        <div class="dropdown p-dropdown">
                                            <a class="dropdown-toggle {{ $todo->level == "high" ? "danger" : ""}} {{ $todo->level == "medium" ? "warning" : ""}} {{ $todo->level == "low" ? "primary" : ""}}" href="#" role="button"
                                                id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-alert-octagon">
                                                    <polygon
                                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                    </polygon>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12" y2="16"></line>
                                                </svg>
                                            </a>

                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                <a class="dropdown-item danger" href="{{ url("/todo/level/") }}/{{ $todo->id }}/high"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> High</a>
                                                <a class="dropdown-item warning" href="{{ url("/todo/level/") }}/{{ $todo->id }}/medium"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> Middle</a>
                                                <a class="dropdown-item primary" href="{{ url("/todo/level/") }}/{{ $todo->id }}/low"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> Low</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-dropdown custom-dropdown-icon">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-more-vertical">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg>
                                            </a>

                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                @if($todo->status != "important")
                                                <a class="important dropdown-item" href="{{ url("/todo/status/") }}/{{ $todo->id }}/important">Important</a>
                                                @else
                                                <a class="important dropdown-item" href="{{ url("/todo/status/") }}/{{ $todo->id }}/normal">Normal</a>
                                                @endif
                                                <a class="dropdown-item delete" href="{{ url("/todo/delete/") }}/{{ $todo->id }}">Delete</a>
                                                <a class="dropdown-item permanent-delete"
                                                href="{{ url("/todo/forceDelete/") }}/{{ $todo->id }}">Permanent Delete</a>
                                                <a class="dropdown-item revive" href="{{ url("/todo/restore/") }}/{{ $todo->id }}">Revive
                                                    Task</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                            @foreach ($trasheds as $todo)
                            <div class="todo-item todo-task-trash">
                                <div class="todo-item-inner">
                                    <div class="n-chk text-center">
                                        <div class="form-check form-check-primary form-check-inline mt-1 me-0"
                                            data-bs-toggle="collapse" data-bs-target>
                                            <input class="form-check-input inbox-chkbox" onclick="markDone({{ $todo->id }})" {{ $todo->status == 'done' ? 'checked' : ''}} type="checkbox">
                                        </div>
                                    </div>

                                    <div class="todo-content" style="width:81%;">
                                        <h5 class="todo-heading" data-todoHeading="{{ $todo->title }}">
                                            {{ $todo->title }}</h5>
                                        <p class="todo-text"
                                            data-todoHtml="<p>{{ $todo->notes }}</p>"
                                            data-todoText='[{"insert":"{{ $todo->notes }}"}]'
                                            data-due='[{"date":"{{ $todo->due }}"}]'>
                                            {{ $todo->notes }}</p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-danger">Due: {{ date("d M Y", strtotime($todo->due)) }}</span>
                                    </div>
                                    <div class="priority-dropdown custom-dropdown-icon">

                                        <div class="dropdown p-dropdown">
                                            <a class="dropdown-toggle {{ $todo->level == "high" ? "danger" : ""}} {{ $todo->level == "medium" ? "warning" : ""}} {{ $todo->level == "low" ? "primary" : ""}}" href="#" role="button"
                                                id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-alert-octagon">
                                                    <polygon
                                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                    </polygon>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12" y2="16"></line>
                                                </svg>
                                            </a>

                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                <a class="dropdown-item danger" href="{{ url("/todo/level/") }}/{{ $todo->id }}/high"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> High</a>
                                                <a class="dropdown-item warning" href="{{ url("/todo/level/") }}/{{ $todo->id }}/medium"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> Middle</a>
                                                <a class="dropdown-item primary" href="{{ url("/todo/level/") }}/{{ $todo->id }}/low"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-alert-octagon">
                                                        <polygon
                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                        </polygon>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12" y2="16"></line>
                                                    </svg> Low</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-dropdown custom-dropdown-icon">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-more-vertical">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg>
                                            </a>

                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                @if($todo->status != "important")
                                                <a class="important dropdown-item" href="{{ url("/todo/status/") }}/{{ $todo->id }}/important">Important</a>
                                                @else
                                                <a class="important dropdown-item" href="{{ url("/todo/status/") }}/{{ $todo->id }}/normal">Normal</a>
                                                @endif
                                                <a class="dropdown-item delete" href="{{ url("/todo/delete/") }}/{{ $todo->id }}">Delete</a>
                                                <a class="dropdown-item permanent-delete"
                                                href="{{ url("/todo/forceDelete/") }}/{{ $todo->id }}">Permanent Delete</a>
                                                <a class="dropdown-item revive" href="{{ url("/todo/restore/") }}/{{ $todo->id }}">Revive
                                                    Task</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="modal fade" id="todoShowListItem" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="task-heading modal-title mb-0"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-x">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="compose-box">
                                            <div class="compose-content">
                                                <p class="task-text"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title add-title" id="addTaskModalTitleLabel1">Add Task</h5>
                            <h5 class="modal-title edit-title" id="addTaskModalTitleLabel2" style="display: none;">Edit Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="compose-box">
                                <div class="compose-content" id="addTaskModalTitle">
                                    <form method="get" id="addForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mail-to mb-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-edit-3 flaticon-notes">
                                                        <path d="M12 20h9"></path>
                                                        <path
                                                            d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                        </path>
                                                    </svg>
                                                    <div class="w-100">
                                                        <input id="task" type="text" placeholder="Task Title" class="form-control" name="title">
                                                        <input id="id" type="hidden" name="id">
                                                        <span class="validation-text"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex mail-subject mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-file-text flaticon-menu-list">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                </path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            <div class="w-100">
                                                <textarea rows="6" class="form-control" id="notes" name="notes" placeholder="Notes"></textarea>
                                                <span class="validation-text"></span>
                                            </div>
                                        </div>
                                        <div class="d-flex mail-subject">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            <div class="w-100">
                                                <input type="date" min="{{ date("Y-m-d") }}" name="due" id="due" class="form-control">
                                                <span class="validation-text"></span>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>
                                Discard</button>
                            <button class="btn add-tsk btn-primary">Add Task</button>
                            <button class="btn edit-tsk btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('more-css')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/editors/quill/quill.snow.css') }}">
<link href="{{ asset('src/assets/css/light/apps/todolist.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/editors/quill/quill.snow.css') }}">
<link href="{{ asset('src/assets/css/dark/apps/todolist.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
     <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
@endsection
@section('more-script')

<script src="{{ asset('src/plugins/src/editors/quill/quill.js') }}"></script>
{{-- <script src="{{ asset('src/assets/js/apps/todoList.js') }}"></script> --}}
<script>
    $(".add-tsk").click(function(){
        var data = $("#addForm").serialize();
        $.ajax({
            url : "{{ url('/todo/store') }}",
            data : data,
            method : "get",
            success : function (response){
                window.location.reload();
            }
        });
    });
    $(".edit-tsk").click(function(){
        var data = $("#addForm").serialize();
        $.ajax({
            url : "{{ url('/todo/update') }}",
            data : data,
            method : "get",
            success : function (response){
                window.location.reload();
            }
        });
    });
   function markDone(id){
    window.open("{{ url('/todo/status/') }}/"+id+"/done", "_self");
   }
$('.input-search').on('keyup', function() {

  var rex = new RegExp($(this).val(), 'i');
    $('.todo-box .todo-item').hide();
    $('.todo-box .todo-item').filter(function() {
        return rex.test($(this).text());
    }).show();
});

const taskViewScroll = new PerfectScrollbar('.task-text', {
    wheelSpeed:.5,
    swipeEasing:!0,
    minScrollbarLength:40,
    maxScrollbarLength:300,
    suppressScrollX : true
});
function dynamicBadgeNotification( setTodoCategoryCount ) {
  var todoCategoryCount = setTodoCategoryCount;

  // Get Parents Div(s)
  var get_ParentsDiv = $('.todo-item');
  var get_TodoAllListParentsDiv = $('.todo-item.all-list');
  var get_TodoCompletedListParentsDiv = $('.todo-item.todo-task-done');
  var get_TodoImportantListParentsDiv = $('.todo-item.todo-task-important');

  // Get Parents Div(s) Counts
  var get_TodoListElementsCount = get_TodoAllListParentsDiv.length;
  var get_CompletedTaskElementsCount = get_TodoCompletedListParentsDiv.length;
  var get_ImportantTaskElementsCount = get_TodoImportantListParentsDiv.length;

  // Get Badge Div(s)
  var getBadgeTodoAllListDiv = $('#all-list .todo-badge');
  var getBadgeCompletedTaskListDiv = $('#todo-task-done .todo-badge');
  var getBadgeImportantTaskListDiv = $('#todo-task-important .todo-badge');


  if (todoCategoryCount === 'allList') {
    if (get_TodoListElementsCount === 0) {
      getBadgeTodoAllListDiv.text('');
      return;
    }
    if (get_TodoListElementsCount > 9) {
        getBadgeTodoAllListDiv.css({
            padding: '2px 0px',
            height: '25px',
            width: '25px'
        });
    } else if (get_TodoListElementsCount <= 9) {
        getBadgeTodoAllListDiv.removeAttr('style');
    }
    getBadgeTodoAllListDiv.text(get_TodoListElementsCount);
  }
  else if (todoCategoryCount === 'completedList') {
    if (get_CompletedTaskElementsCount === 0) {
      getBadgeCompletedTaskListDiv.text('');
      return;
    }
    if (get_CompletedTaskElementsCount > 9) {
        getBadgeCompletedTaskListDiv.css({
            padding: '2px 0px',
            height: '25px',
            width: '25px'
        });
    } else if (get_CompletedTaskElementsCount <= 9) {
        getBadgeCompletedTaskListDiv.removeAttr('style');
    }
    getBadgeCompletedTaskListDiv.text(get_CompletedTaskElementsCount);
  }
  else if (todoCategoryCount === 'importantList') {
    if (get_ImportantTaskElementsCount === 0) {
      getBadgeImportantTaskListDiv.text('');
      return;
    }
    if (get_ImportantTaskElementsCount > 9) {
        getBadgeImportantTaskListDiv.css({
            padding: '2px 0px',
            height: '25px',
            width: '25px'
        });
    } else if (get_ImportantTaskElementsCount <= 9) {
        getBadgeImportantTaskListDiv.removeAttr('style');
    }
    getBadgeImportantTaskListDiv.text(get_ImportantTaskElementsCount);
  }
}

new dynamicBadgeNotification('allList');
new dynamicBadgeNotification('completedList');
new dynamicBadgeNotification('importantList');

/*
  ====================
    Quill Editor
  ====================
*/

var quill = new Quill('#taskdescription', {
  modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline'],
      ['image', 'code-block']
    ]
  },
  placeholder: 'Compose an epic...',
  theme: 'snow'  // or 'bubble'
});

$('#addTaskModal').on('hidden.bs.modal', function (e) {
  // do something...
  $(this)
    .find("input,textarea,select")
       .val('')
       .end();

  quill.deleteText(0, 2000);
})
$('.mail-menu').on('click', function(event) {
  $('.tab-title').addClass('mail-menu-show');
  $('.mail-overlay').addClass('mail-overlay-show');
})
$('.mail-overlay').on('click', function(event) {
  $('.tab-title').removeClass('mail-menu-show');
  $('.mail-overlay').removeClass('mail-overlay-show');
})
function showModal() {

  $('.add-tsk').show();
  $('.edit-tsk').hide();
  $('.add-title').show();
  $('.edit-title').hide();
  $('#addTaskModal').modal('show');
  const ps = new PerfectScrollbar('.todo-box-scroll', {
    suppressScrollX : true
  });
}
const ps = new PerfectScrollbar('.todo-box-scroll', {
    suppressScrollX : true
  });

const todoListScroll = new PerfectScrollbar('.todoList-sidebar-scroll', {
    suppressScrollX : true
  });

  $('.todo-item input[type="checkbox"]').click(function() {
    if ($(this).is(":checked")) {
        $(this).parents('.todo-item').addClass('todo-task-done');
    }
    else if ($(this).is(":not(:checked)")) {
        $(this).parents('.todo-item').removeClass('todo-task-done');
    }
    new dynamicBadgeNotification('completedList');
  });


  $('.action-dropdown .dropdown-menu .delete.dropdown-item').click(function() {
    if(!$(this).parents('.todo-item').hasClass('todo-task-trash')) {

        var getTodoParent = $(this).parents('.todo-item');
        var getTodoClass = getTodoParent.attr('class');

        var getFirstClass = getTodoClass.split(' ')[1];
        var getSecondClass = getTodoClass.split(' ')[2];
        var getThirdClass = getTodoClass.split(' ')[3];

        if (getFirstClass === 'all-list') {
          getTodoParent.removeClass(getFirstClass);
        }
        if (getSecondClass === 'todo-task-done' || getSecondClass === 'todo-task-important') {
          getTodoParent.removeClass(getSecondClass);
        }
        if (getThirdClass === 'todo-task-done' || getThirdClass === 'todo-task-important') {
          getTodoParent.removeClass(getThirdClass);
        }
        $(this).parents('.todo-item').addClass('todo-task-trash');
    } else if($(this).parents('.todo-item').hasClass('todo-task-trash')) {
        $(this).parents('.todo-item').removeClass('todo-task-trash');
    }
    new dynamicBadgeNotification('allList');
    new dynamicBadgeNotification('completedList');
    new dynamicBadgeNotification('importantList');
  });


  $('.action-dropdown .dropdown-menu .revive.dropdown-item').on('click', function(event) {

    if($(this).parents('.todo-item').hasClass('todo-task-trash')) {
      var getTodoParent = $(this).parents('.todo-item');
      var getTodoClass = getTodoParent.attr('class');
      var getFirstClass = getTodoClass.split(' ')[1];
      $(this).parents('.todo-item').removeClass(getFirstClass);
      $(this).parents('.todo-item').addClass('all-list');
      $(this).parents('.todo-item').hide();
    }
    new dynamicBadgeNotification('allList');
    new dynamicBadgeNotification('completedList');
    new dynamicBadgeNotification('importantList');
  });


  $('.action-dropdown .dropdown-menu .edit.dropdown-item').click(function() {

    event.preventDefault();

    var $_outerThis = $(this);

    $('.add-tsk').hide();
    $('.edit-tsk').show();

    $('.add-title').hide();
    $('.edit-title').show();


    var $_taskTitle = $_outerThis.parents('.todo-item').children().find('.todo-heading').attr('data-todoHeading');
    var $_taskText = $_outerThis.parents('.todo-item').children().find('.todo-text').attr('data-todoText');
    var $_taskDue = $_outerThis.parents('.todo-item').children().find('.todo-text').attr('data-due');
    var $_taskId = $_outerThis.parents('.todo-item').children().find('.todo-text').attr('data-id');
    var $_taskJson = JSON.parse($_taskText);
    var $_taskDueDate = JSON.parse($_taskDue);
    var $_taskID = JSON.parse($_taskId);


    $('#task').val($_taskTitle);
    $('#notes').text($_taskJson[0].insert);
    $('#due').val($_taskDueDate[0].date);
    $('#id').val($_taskID[0].id);


    $('#addTaskModal').modal('show');
  });



  $('.todo-item .todo-content').on('click', function(event) {

    event.preventDefault();

    var $_taskTitle = $(this).find('.todo-heading').attr('data-todoHeading');
    var $todoHtml = $(this).find('.todo-text').attr('data-todoHtml');
    var $todoDue = $(this).find('.todo-text').attr('data-due');

    $('.task-heading').text($_taskTitle);
    $('.task-text').html($todoHtml);

    $('#todoShowListItem').modal('show');
  });

var $btns = $('.list-actions').click(function() {
  if (this.id == 'all-list') {
    var $el = $('.' + this.id).fadeIn();
    $('#ct > div').not($el).hide();
  } else if (this.id == 'todo-task-trash') {
    var $el = $('.' + this.id).fadeIn();
    $('#ct > div').not($el).hide();
  } else {
    var $el = $('.' + this.id).fadeIn();
    $('#ct > div').not($el).hide();
  }
  $btns.removeClass('active');
  $(this).addClass('active');
})


$('.tab-title .nav-pills a.nav-link').on('click', function(event) {
  $(this).parents('.mail-box-container').find('.tab-title').removeClass('mail-menu-show')
  $(this).parents('.mail-box-container').find('.mail-overlay').removeClass('mail-overlay-show')
})

// Validation Process

  var $_getValidationField = document.getElementsByClassName('validation-text');

  getTaskTitleInput = document.getElementById('task');

  getTaskTitleInput.addEventListener('input', function() {

      getTaskTitleInputValue = this.value;

      if (getTaskTitleInputValue == "") {
        $_getValidationField[0].innerHTML = 'Title Required';
        $_getValidationField[0].style.display = 'block';
      } else {
        $_getValidationField[0].style.display = 'none';
      }
  })

  getTaskDescriptionInput = document.getElementById('taskdescription');

  getTaskDescriptionInput.addEventListener('input', function() {

    getTaskDescriptionInputValue = this.value;

    if (getTaskDescriptionInputValue == "") {
      $_getValidationField[1].innerHTML = 'Description Required';
      $_getValidationField[1].style.display = 'block';
    } else {
      $_getValidationField[1].style.display = 'none';
    }

  })

</script>
@endsection
