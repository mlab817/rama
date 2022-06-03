<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-danger">
            {{ $notifications->count() }}
        </span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have {{ $notifications->count() }} notifications</li>
        <li>

            <ul class="menu">
                @forelse($notifications as $notification)
                <li>
                    @php
                        $formId = 'notification-' . $notification->id;
                    @endphp
                    <form id="{{$formId}}" action="{{ route('notifications.read') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $notification->id }}" name="notification_id">
                    </form>
                    <a target="_blank" onclick="document.getElementById('{{$formId}}').submit(); return true" href="{{ $notification->data['actionUrl'] }}">
                        <div class="pull-left">
                            <img height="40" width="40" class="img-circle" src="https://robohash.org/{{ $notification->data['from'] }}?set=set3" alt="{{ $notification->data['from']  }}">
                        </div>
                        <h5>
                            {{ $notification->data['from'] }}
                            <small class="pull-right">
                                <i class="fa fa-clock-o"></i>
                                {{ $notification->created_at->diffForHumans(null, null, true) }}
                            </small>
                        </h5>
                        <small>{{ $notification->data['message'] }}</small>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li class="footer"><a href="#">View all</a></li>
    </ul>
</li>
