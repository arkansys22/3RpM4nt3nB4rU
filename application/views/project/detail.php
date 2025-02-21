<h3>Daftar crews dalam Proyek</h3>
<table border="1">
    <tr>
        <th>Peran</th>
        <th>Nama crews</th>
    </tr>
    <?php foreach ($selected_crews as $role => $crews): ?>
        <tr>
            <td><?= $role ?></td>
            <td><?= $crews['crew_name'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
