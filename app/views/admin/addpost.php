<div class="container-fluid">
  <h1>New Post</h1>
  <p>Add a new article to the blog.</p>
  <div class="row">
    <div class="col-xs-4" style="background-color:lavender;"></div>
    <div class="col-sm-4" style="background-color:lavenderblush;">
        <form class="form-horizontal" role="form" method="post" action="<?= Loc::url('admin/addpost'); ?>">
            <div class="form-group">
                <label class="control-label col-sm-2" for="title">Title:</label>
                <div class="col-sm-10">
                    <input name ="title" type="text" class="form-control" id="title" placeholder="Enter title">
                </div>
            </div>
            <div class="form-group">
                <label for="blogpost"> Blog Post:</label>
                <div class="col-sm-10">
                    <textarea name="content" class="form-control" rows="5" id="blogpost"></textarea>
                </div>
            </div>

            <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-sm-4" style="background-color:lavender;"></div>
  </div>
</div>



