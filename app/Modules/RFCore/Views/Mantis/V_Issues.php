<?php
/** @var $data array */
?>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th>Summary</th>
            <th>Description</th>
            <th>Project</th>
            <th>Status</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
        <?php foreach ($data as $issue): ?>
        <tr>
            <td><?= $issue->id ?></td>
            <td><?= $issue->summary ?></td>
            <td><?= $issue->description ?></td>
            <td><?= $issue->project->name ?></td>
            <td><?= $issue->status->name ?></td>
            <td><?= date_create($issue->created_at)->format('d/m/Y') ?></td>
            <td><?= date_create($issue->updated_at)->format('d/m/Y') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
