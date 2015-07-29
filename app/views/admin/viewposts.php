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
        <td><?= $entry['id']; ?></td>
        <td><?= $entry['title']; ?></td>
        <td><?= $entry['content']; ?>.com</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>