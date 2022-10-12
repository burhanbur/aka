
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('home') }}" aria-expanded="false">
                                <i data-feather="home" class="feather-icon"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        @hasrole('admin')
                        <li class="list-divider"></li>
                        <!-- <li class="nav-small-cap"><span class="hide-menu">Master Data</span></li> -->

                        <li class="sidebar-item @if (Request::is('cpanel/institutions*')) selected @endif"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('institutions') }}" aria-expanded="false">
                                <i data-feather="slack" class="feather-icon"></i>
                                <span class="hide-menu">Perguruan Tinggi</span>
                            </a>
                        </li>

                        <li class="sidebar-item @if (Request::is('cpanel/users*')) selected @endif"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('users') }}" aria-expanded="false">
                                <i data-feather="users" class="feather-icon"></i>
                                <span class="hide-menu">Pengguna</span>
                            </a>
                        </li>

                        <li class="sidebar-item @if (Request::is('cpanel/activities*')) selected @endif"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('activities') }}" aria-expanded="false">
                                <i data-feather="briefcase" class="feather-icon"></i>
                                <span class="hide-menu">Kegiatan</span>
                            </a>
                        </li>

                        <li class="sidebar-item @if (Request::is('cpanel/surveys*')) selected @endif"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('surveys') }}" aria-expanded="false">
                                <i data-feather="folder" class="feather-icon"></i>
                                <span class="hide-menu">Survey</span>
                            </a>
                        </li>

                        @endhasrole

                        @role('user')
                        <li class="sidebar-item @if (Request::is('cpanel/activities*')) selected @endif"> 
                            <a class="sidebar-link sidebar-link" href="{{ route('users') }}" aria-expanded="false">
                                <i data-feather="briefcase" class="feather-icon"></i>
                                <span class="hide-menu">Kegiatan</span>
                            </a>
                        </li>
                        @endrole
                        
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->