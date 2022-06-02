<div class="small-box bg-{{ $cardColor ?? 'aqua' }}">
    <div class="inner">
        <h3>{{ $cardValue ?? 0 }}</h3>
        <p>{{ $cardTitle ?? 'Title Placeholder' }}</p>
    </div>
    <div class="icon">
        <i class="fa fa-{{ $cardIcon ?? 'bar-chart' }}"></i>
    </div>
    <a href="{{ $cardAction ?? '#' }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
</div>
