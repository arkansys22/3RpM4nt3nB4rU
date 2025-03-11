<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="shortcut icon" href="./assets/img/favicon.ico" />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="./assets/img/apple-icon.png"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit/compiled-tailwind.min.css"
    />
    <title>Client Login Mantenbaru Organizer</title>
  </head>
  <body class="text-gray-800 antialiased">
    <main>
      <section class="absolute w-full h-full">
        <div
          class="absolute top-0 w-full h-full bg-white"
          style="background-image: url(<?= base_url('assets/backend/src/images/logo/bg-login.png') ?>); background-size: cover; background-repeat: no-repeat;"
        ></div>
        <div class="container mx-auto px-4 h-full">
          <div class="flex content-center items-center justify-center h-full">
            <div class="w-full lg:w-4/12 px-4">
              <div
                class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-transparent border-0"
                style="background-color: rgba(255, 255, 255, 0.8);"
              >
                <div class="rounded-t mb-0 px-6 py-6">
                    <img src="<?= base_url('assets/backend/src/images/logo/logo mantenbaru merah-02.png') ?>" alt="Logo" style="width: 220px; margin: 0 auto;">
                </div>
                <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                  <form action="<?= base_url('client/login') ?>" method="post">
                    <?php echo $this->session->flashdata('login_failed'); ?>
                    <?php echo $this->session->flashdata('msg'); ?>
                    <?php
                      if ($this->input->post('email')!=''){
                        echo "<div class='alert bg-5'><center>$title</center></div>";
                      }elseif($this->input->post('username')!=''){
                        echo "<div class='alert bg-5'><center>$title</center></div>";
                      }
                      echo form_open('client/login');
                    ?>
                    <div class="mb-4">
                      <label class="mb-2.5 block font-medium text-black dark:text-white">Username </label>
                      <div class="relative">
                        <input type="text" name='username' onkeyup="this.value = this.value.toLowerCase()" value="<?php echo set_value('username') ?>" placeholder="Enter your username" class="w-full rounded-lg border border-stroke bg-transparent py-4 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"/>
                        <span class="absolute right-4 top-4">
                          <svg class="fill-current" width="22" height="22" viewBox="0 0 22 22" fill="none"></svg>
                        </span>
                        <span style="font-size: 15px; color:grey;"><?php echo form_error('username'); ?></span>
                      </div>
                    </div>
                    <div class="mb-6">
                      <label class="mb-2.5 block font-medium text-black dark:text-white">Password </label>
                      <div class="relative">
                        <input type="password" name='password' placeholder="6+ Characters, 1 Capital letter" class="w-full rounded-lg border border-stroke bg-transparent py-4 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" >
                      </div>
                      <span style="font-size: 15px; color:grey;"><?php echo form_error('password'); ?></span>
                    </div>
                    <div class="mb-5">
                      <input type="submit" name="submit" value="Sign In" class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90" style="background-color: #ed126b; border-color: #ed126b;"/>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="absolute w-full bottom-0 bg-white pb-6">
          <div class="container mx-auto px-4">
            <hr class="mb-6 border-b-1 border-gray-700" />
            <div
              class="flex flex-wrap items-center md:justify-between justify-center"
            >
              <div class="w-full md:w-4/12 px-4">
                <div class="text-sm text-gray-900 font-semibold py-1">
                  Copyright © 2025 Mantenbaru Organizer
                </div>
              </div>

            </div>
          </div>
        </footer>
      </section>
    </main>
  </body>
</html>