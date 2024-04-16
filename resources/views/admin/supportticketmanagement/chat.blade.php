@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'Customer Enquiry')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-0">
                    <div class="card">
                        <div class="p-3 border-bottom">
                            <div class="row">
                                <div class="col-xl-4 col-7">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar me-3 d-sm-block d-none">
                                            <img src="assets/images/users/avatar-9.jpg" alt=""
                                                class="img-thumbnail d-block rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-20 mb-1 text-truncate"><a href="#"
                                                    class="text-dark">{{ ucfirst($data->customer->first_name) . ' ' . ucfirst($data->customer->last_name) }}</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-5">
                                    <ul class="list-inline user-chat-nav text-end mb-0">
                                        <li class="list-inline-item">
                                            <div class="dropdown">
                                                <button class="btn nav-btn dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="uil uil-search"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                                                    <form class="px-2">
                                                        <div>
                                                            <input type="text" class="form-control bg-light rounded"
                                                                placeholder="Search...">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- end li -->
                                        <li class="list-inline-item">
                                            <div class="dropdown">
                                                <button class="btn nav-btn dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="uil uil-ellipsis-h"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Profile</a>
                                                    <a class="dropdown-item" href="#">Archive</a>
                                                    <a class="dropdown-item" href="#">Muted</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- end li -->
                                    </ul>
                                    <!-- end ul -->
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="chat-conversation p-3" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: -16px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: -17px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper"
                                                style="height: 100%; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 16px;">
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($conversation as $chat)
                                                            <li class="{{ $chat->reply_by ? 'right' : '' }}">
                                                                <div class="conversation-list">
                                                                    <div class="ctext-wrap">
                                                                        <div class="chat-avatar">
                                                                            <img src="{{ $chat->reply_by ? 'assets/images/users/avatar-3.jpg' : 'assets/images/users/avatar-9.jpg' }}"
                                                                                alt="avatar">
                                                                        </div>
                                                                        <div class="ctext-wrap-content">
                                                                            <p
                                                                                class="mb-0 {{ $chat->sent ? 'text-end' : 'text-start' }}">
                                                                                {{ $chat->message }}</p>
                                                                            <h5 class="conversation-name"><span
                                                                                    class="time">{{ $chat->created_at->format('H:i') }}</span>
                                                                            </h5>
                                                                        </div>
                                                                        <div class="dropdown align-self-start">
                                                                            <a class="dropdown-toggle" href="#"
                                                                                role="button" data-bs-toggle="dropdown"
                                                                                aria-haspopup="true" aria-expanded="false">
                                                                                <i class="uil uil-ellipsis-v"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item"
                                                                                    href="#">Copy</a>
                                                                                <a class="dropdown-item"
                                                                                    href="#">Save</a>
                                                                                <a class="dropdown-item"
                                                                                    href="#">Forward</a>
                                                                                <a class="dropdown-item"
                                                                                    href="#">Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    <!-- end ul -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: auto; height: 1289px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar"
                                        style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar"
                                        style="height: 25px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 chat-input-section">
                            <form action="{{route('message.post')}}" method="post">
                                @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="position-relative">
                                        <input type="text" class="form-control chat-input"
                                            placeholder="Enter Message..." name="message">
                                            <input type="hidden" name="ticket_no" value="{{$data->ticket_no}}">

                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary chat-send w-md"><span
                                            class="d-none d-sm-inline-block me-2">Send</span> <i
                                            class="mdi mdi-send float-end"></i></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
