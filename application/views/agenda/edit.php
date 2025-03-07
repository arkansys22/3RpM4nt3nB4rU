<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <h1>Edit Agenda for <?= $agenda->client_name ?></h1>
    <form action="<?= site_url('agenda/update') ?>" method="post">
        <input type="hidden" name="id_session" value="<?= $agenda->id_session ?>">
        <label for="client_name">Client Name:</label>
        <input type="text" id="client_name" name="client_name" value="<?= $agenda->client_name ?>" readonly><br>
        <label for="brainstorming">Brainstorming:</label>
        <input type="date" id="brainstorming" name="brainstorming" value="<?= $agenda->brainstorming ?>"><br>
        <label for="technical_meeting">Technical Meeting:</label>
        <input type="date" id="technical_meeting" name="technical_meeting" value="<?= $agenda->technical_meeting ?>"><br>
        <label for="final_revision">Final Revision:</label>
        <input type="date" id="final_revision" name="final_revision" value="<?= $agenda->final_revision ?>"><br>
        <label for="loading_decoration">Loading Decoration:</label>
        <input type="date" id="loading_decoration" name="loading_decoration" value="<?= $agenda->loading_decoration ?>"><br>
        <label for="wedding_day">Wedding Day:</label>
        <input type="date" id="wedding_day" name="wedding_day" value="<?= $agenda->wedding_day ?>"><br>
        <label for="honeymoon">Honeymoon:</label>
        <input type="date" id="honeymoon" name="honeymoon" value="<?= $agenda->honeymoon ?>"><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
