@extends("layouts.layout")
@section("content")
<form action="action" method="post" enctype="multipart/form-data">
    @csrf
    <h3>Создать пост</h3>
    <div class="form-group">
        <input type="text" class="form-control" name="title" required>
            
    </div>
    <div class="form-group">
        <textarea id="createTextArea1" name="description" rows="10" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <input type="file" name="img">
       
    </div>
    <input type="submit" value="Создать пост" class="btn btn-outline-success" onclick="">
</form>
    

@endsection

