<style>
ul li { list-style: none; }
</style>

<h3>Flash News</h3>
@if(isset($news))

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 100px;"></th>
            <th style="width: 100px;"></th>
        </tr>
    </thead>

    <tbody>
        @foreach($news as $the_news)
        <tr>
            <td><a href="{{ $news_path.$the_news->slug }}">{{ $the_news->title }}</td>
            <td>{{ date(APP_DATE_FORMAT, strtotime($the_news->created_at)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
   
@else
<h3>No news</h3>
@endif