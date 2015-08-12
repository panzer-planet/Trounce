<div class="container">
  <h2>View Posts</h2>
  <p>From here we can manage the posts:</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($entries as $entry): ?>
      <tr>
        <form method="post" action="/admin/editPosts">
            <td><?= $entry['id']; ?></td>
            <td>
                
                <input type="text" name="title" value="<?= $entry['title']; ?>" placeholder="title"/>
            </td>
            <td>
                <input type="text" name="content" value="<?= $entry['content']; ?>" placeholder="content"/>
                <button>Edit</button>
            <td>
            <input type="hidden" name="id" value="<?= $entry['id']; ?>"/>
        </form>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>