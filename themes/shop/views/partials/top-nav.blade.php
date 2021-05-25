<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <chat-messages></chat-messages>
    </li>
    <li class="nav-item dropdown">
       
        <div class="d-flex">
            <div class="image">
                <img src="{{ URL::asset('/themes/backend/images/avatar.svg') }}" class="img-circle elevation-2" alt="User Image" width="35">
            </div>
            <div class="info">
                <a class="nav-link" data-toggle="dropdown" href="#"><span>{{ $currentUser->name }}</span> </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    <a href="{{ route('admin.users.edit', ['user' => $currentUser->id]) }}"
                       class="dropdown-item dropdown-footer">
                        {{trans('backend::profile.label.profile')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item dropdown-footer" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('admin-logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>


    </li>


</ul>


