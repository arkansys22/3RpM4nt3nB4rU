<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Login Pengguna | Mantenbaru Wedding Organizer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body {
        background-image: url('<?php echo base_url()?>assets/backend/bglog.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }
    </style>
  </head>

  <body class="bg-white dark:bg-boxdark-2 text-black dark:text-white">
    <div class="flex h-screen items-center justify-center">
      <div class="w-full max-w-md p-6 bg-white/70 dark:bg-boxdark-2/70 rounded-lg shadow-md">
        <div class="text-center">
          <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" alt="Logo" style="width: 320px; margin: 0 auto;">
        </div>
        <br>
        <?php echo $this->session->flashdata('login_failed'); ?>
        <?php echo $this->session->flashdata('msg'); ?>
        <?php
          if ($this->input->post('email') != '') {
            echo "<div class='alert bg-5'><center>$title</center></div>";
          } elseif ($this->input->post('username') != '') {
            echo "<div class='alert bg-5'><center>$title</center></div>";
          }
          echo form_open('login');
        ?>
        <form>
		  <div class="mb-4">
			<div class="relative">
			  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 h-6 text-black">
				<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
			  </svg>
			  <input type="text" name="username" onkeyup="this.value = this.value.toLowerCase()" value="<?php echo set_value('username') ?>" placeholder="Username" class="w-full pl-12 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-gray-600 dark:border-gray-600 dark:bg-form-input dark:focus:border-gray-400" />
			</div>
			<span class="text-sm text-gray-00"><?php echo form_error('username'); ?></span>
		  </div>
		  <div class="mb-4">
			<div class="relative">
			  <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 h-6">
				<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
			  </svg>
			  <input type="password" name="password" placeholder="Password" class="w-full pl-12 rounded-lg border border-gray-400 bg-transparent py-2 px-4 outline-none focus:border-gray-600 dark:border-gray-600 dark:bg-form-input dark:focus:border-gray-400" />
			</div>
			<span class="text-sm text-gray-00"><?php echo form_error('password'); ?></span>
		  </div>
		  <div class="mb-4">
			<input type="submit" name="submit" value="Sign In" class="w-full cursor-pointer rounded-lg bg-[#ed126b] text-white py-2 px-4 font-medium hover:bg-opacity-90" />
		  </div>
		  <footer class="text-center mt-4 text-sm text-black">
			Copyright © 2025 Mantenbaru Organizer
		  </footer>
		  <!-- <div class="text-center">
			<p class="font-medium">
			  Don’t have an account? <a href="#" class="text-[#ed126b] hover:underline">Sign Up</a>
			</p>
		  </div> -->
        </form>
      </div>
    </div>
    <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');

        const changeTextColor = (input) => {
          input.style.color = '#ed126b';
        };

        usernameInput.addEventListener('input', () => changeTextColor(usernameInput));
        passwordInput.addEventListener('input', () => changeTextColor(passwordInput));
      });
    </script>
  </body>
</html>
