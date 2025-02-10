<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	    <title>Login Pengguna | Mantenbaru Wedding Organizer</title>
	    <meta name="title" content="Vendor Pernikahan Terlengkap | Mantenbaru Wedding Organizer">
	    <meta name="site_url" content="<?php echo base_url()?>">
	    <meta name="description" content="">
	    <meta name="keywords" content="mantenbaru.com, mantenbaru, perencanaan investasi, vendor pernikahan, wedding organizer, wedding planner">

	    <meta NAME="ROBOTS" CONTENT="NOINDEX">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="web_author" content="dhawyarkan.com">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	    <meta name="apple-mobile-web-app-capable" content="yes" />
	    <meta property="og:site_name" content="Mantenbaru">
	    <meta property="og:title" content="Vendor Pernikahan Terlengkap | Mantenbaru Wedding Organizer">
	    <meta property="og:description" content="">
	    <meta property="og:url" content="<?php echo base_url()?>">
	    <meta property="og:image" content="<?php echo base_url()?>asset/frontend/aspanel/img/logo.png">
	    <meta property="og:image:url" content="<?php echo base_url()?>asset/frontend/aspanel/img/logo.png">
	    <meta property="og:type" content="article">
	    <link rel="icon" href="<?php echo base_url()?>assets/backend/favicon.ico" type="image/x-icon">
	    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>

  		<script data-cfasync="false" nonce="b305daaa-d375-4b11-b0cc-8a54017bb2e4">try{(function(w,d){!function(a,b,c,d){if(a.zaraz)console.error("zaraz is loaded twice");else{a[c]=a[c]||{};a[c].executed=[];a.zaraz={deferred:[],listeners:[]};a.zaraz._v="5848";a.zaraz._n="b305daaa-d375-4b11-b0cc-8a54017bb2e4";a.zaraz.q=[];a.zaraz._f=function(e){return async function(){var f=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:f})}};for(const g of["track","set","debug"])a.zaraz[g]=a.zaraz._f(g);a.zaraz.init=()=>{var h=b.getElementsByTagName(d)[0],i=b.createElement(d),j=b.getElementsByTagName("title")[0];j&&(a[c].t=b.getElementsByTagName("title")[0].text);a[c].x=Math.random();a[c].w=a.screen.width;a[c].h=a.screen.height;a[c].j=a.innerHeight;a[c].e=a.innerWidth;a[c].l=a.location.href;a[c].r=b.referrer;a[c].k=a.screen.colorDepth;a[c].n=b.characterSet;a[c].o=(new Date).getTimezoneOffset();if(a.dataLayer)for(const k of Object.entries(Object.entries(dataLayer).reduce(((l,m)=>({...l[1],...m[1]})),{})))zaraz.set(k[0],k[1],{scope:"page"});a[c].q=[];for(;a.zaraz.q.length;){const n=a.zaraz.q.shift();a[c].q.push(n)}i.defer=!0;for(const o of[localStorage,sessionStorage])Object.keys(o||{}).filter((q=>q.startsWith("_zaraz_"))).forEach((p=>{try{a[c]["z_"+p.slice(7)]=JSON.parse(o.getItem(p))}catch{a[c]["z_"+p.slice(7)]=o.getItem(p)}}));i.referrerPolicy="origin";i.src="cdn-cgi/zaraz/sd0d9.js?z="+btoa(encodeURIComponent(JSON.stringify(a[c])));h.parentNode.insertBefore(i,h)};["complete","interactive"].includes(b.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async bs=>new Promise((bt=>{if(bs){bs.e&&bs.e.forEach((bu=>{try{const bv=d.querySelector("script[nonce]"),bw=bv?.nonce||bv?.getAttribute("nonce"),bx=d.createElement("script");bw&&(bx.nonce=bw);bx.innerHTML=bu;bx.onload=()=>{d.head.removeChild(bx)};d.head.appendChild(bx)}catch(by){console.error(`Error executing script: ${bu}\n`,by)}}));Promise.allSettled((bs.f||[]).map((bz=>fetch(bz[0],bz[1]))))}bt()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};
  		</script>
</head>

<body
    x-data="{ page: 'stocks', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
		    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => 	loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
			  <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
			  </div>
			</div>
    <!-- ===== Preloader End ===== -->
    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
     <?php $this->load->view('backend/sidebar')?>

      <!-- ===== Content Area Start ===== -->
      <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        <?php $this->load->view('backend/header')?>

        <!-- ===== Main Content Start ===== -->
        <main>
          <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
	            <!-- ====== Data Stats Start -->
	            <div class="data-stats-slider-outer relative col-span-12 rounded-sm border border-stroke bg-white py-10 shadow-default dark:border-strokedark dark:bg-boxdark" >
	                <div class="dataStatsSlider swiper !-mx-px">
	                  <div class="swiper-wrapper">

	                    <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
	                      	<div class="flex items-center justify-between">
		                        <div class="flex items-center gap-2.5">
		                          <div
		                            class="h-10.5 w-10.5 overflow-hidden rounded-full"
		                          >
		                            <img
		                              src="src/images/brand/brand-07.svg"
		                              alt="brand"
		                            />
		                          </div>
		                          <h4
		                            class="text-xl font-bold text-black dark:text-white"
		                          >
		                            Clients
		                          </h4>
		                        </div>
		                        
	                      	</div>
		                    <div class="mt-5.5 flex flex-col gap-1.5">
		                        <div class="flex items-center justify-between gap-1">
		                          <p class="text-sm font-medium">Total Clients</p>

		                          <p class="font-medium text-black dark:text-white">
		                            $410.50
		                          </p>
		                        </div>

		                        <div class="flex items-center justify-between gap-1">
		                          <span class="text-meta-3">+2.5% than last Week</span>                  	
		                        </div>
		                    </div>
	                    </div>

	                    <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
	                      	<div class="flex items-center justify-between">
		                        <div class="flex items-center gap-2.5">
		                          <div
		                            class="h-10.5 w-10.5 overflow-hidden rounded-full"
		                          >
		                            <img
		                              src="src/images/brand/brand-07.svg"
		                              alt="brand"
		                            />
		                          </div>
		                          <h4
		                            class="text-xl font-bold text-black dark:text-white"
		                          >
		                            Income
		                          </h4>
		                        </div>
		                        
	                      	</div>
		                    <div class="mt-5.5 flex flex-col gap-1.5">
		                        <div class="flex items-center justify-between gap-1">
		                          <p class="text-sm font-medium">Total Income</p>

		                          <p class="font-medium text-black dark:text-white">
		                            $410.50
		                          </p>
		                        </div>

		                        <div class="flex items-center justify-between gap-1">
		                          <span class="text-meta-3">+2.5% than last Week</span>                  	
		                        </div>
		                    </div>
	                    </div>

	                   	<div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
	                      	<div class="flex items-center justify-between">
		                        <div class="flex items-center gap-2.5">
		                          <div
		                            class="h-10.5 w-10.5 overflow-hidden rounded-full"
		                          >
		                            <img
		                              src="src/images/brand/brand-07.svg"
		                              alt="brand"
		                            />
		                          </div>
		                          <h4
		                            class="text-xl font-bold text-black dark:text-white"
		                          >
		                            Outcome
		                          </h4>
		                        </div>
		                        
	                      	</div>
		                    <div class="mt-5.5 flex flex-col gap-1.5">
		                        <div class="flex items-center justify-between gap-1">
		                          <p class="text-sm font-medium">Total Outcome</p>

		                          <p class="font-medium text-black dark:text-white">
		                            $410.50
		                          </p>
		                        </div>

		                        <div class="flex items-center justify-between gap-1">
		                          <span class="text-meta-3">+2.5% than last Week</span>                  	
		                        </div>
		                    </div>
	                    </div>

	                    <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
	                      	<div class="flex items-center justify-between">
		                        <div class="flex items-center gap-2.5">
		                          <div
		                            class="h-10.5 w-10.5 overflow-hidden rounded-full"
		                          >
		                            <img
		                              src="src/images/brand/brand-07.svg"
		                              alt="brand"
		                            />
		                          </div>
		                          <h4
		                            class="text-xl font-bold text-black dark:text-white"
		                          >
		                            Investment
		                          </h4>
		                        </div>
		                        
	                      	</div>
		                    <div class="mt-5.5 flex flex-col gap-1.5">
		                        <div class="flex items-center justify-between gap-1">
		                          <p class="text-sm font-medium">Total Investment</p>

		                          <p class="font-medium text-black dark:text-white">
		                            $410.50
		                          </p>
		                        </div>

		                        <div class="flex items-center justify-between gap-1">
		                          <span class="text-meta-3">+2.5% than last Week</span>                  	
		                        </div>
		                    </div>
	                    </div>                  
	                  </div>
	                </div>

	                <div class="swiper-button-prev">
	                  <svg
	                    class="fill-current"
	                    width="23"
	                    height="23"
	                    viewBox="0 0 23 23"
	                    fill="none"
	                    xmlns="http://www.w3.org/2000/svg"
	                  >
	                    <path
	                      d="M15.8562 2.80185C16.0624 2.80185 16.2343 2.8706 16.4062 3.0081C16.7155 3.31748 16.7155 3.79873 16.4062 4.1081L9.1874 11.4987L16.4062 18.855C16.7155 19.1644 16.7155 19.6456 16.4062 19.955C16.0968 20.2644 15.6155 20.2644 15.3062 19.955L7.5374 12.0487C7.22803 11.7394 7.22803 11.2581 7.5374 10.9487L15.3062 3.04248C15.4437 2.90498 15.6499 2.80185 15.8562 2.80185Z"
	                      fill=""
	                    />
	                  </svg>
	                </div>

	                <div class="swiper-button-next">
	                  <svg
	                    class="fill-current"
	                    width="23"
	                    height="23"
	                    viewBox="0 0 23 23"
	                    fill="none"
	                    xmlns="http://www.w3.org/2000/svg"
	                  >
	                    <path
	                      d="M8.08721 20.1957C7.88096 20.1957 7.70908 20.127 7.53721 19.9895C7.22783 19.6801 7.22783 19.1988 7.53721 18.8895L14.756 11.4988L7.53721 4.14258C7.22783 3.8332 7.22783 3.35195 7.53721 3.04258C7.84658 2.7332 8.32783 2.7332 8.63721 3.04258L16.406 10.9488C16.7153 11.2582 16.7153 11.7395 16.406 12.0488L8.63721 19.9551C8.49971 20.0926 8.29346 20.1957 8.08721 20.1957Z"
	                      fill=""
	                    />
	                  </svg>
	                </div>
	            </div>
	            <!-- ====== Data Stats End -->

				<!-- ====== Button Start -->
				<div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-7">
					<div class="p-4 sm:p-5 xl:p-7.5">
            			<div class="flex flex-col gap-6">
                    		<div class="flex flex-wrap items-center">
                      <a href="#" class="inline-flex items-center gap-2.5 border border-primary bg-primary px-2 py-1 font-medium text-white hover:border-primary hover:bg-primary hover:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M7.5 9.03125C9.42187 9.03125 10.9922 7.46094 10.9922 5.53906C10.9922 3.61719 9.42187 2.02344 7.5 2.02344C5.57812 2.02344 4.00781 3.59375 4.00781 5.51562C4.00781 7.4375 5.57812 9.03125 7.5 9.03125ZM7.5 2.84375C8.97656 2.84375 10.1719 4.03906 10.1719 5.51562C10.1719 6.99219 8.97656 8.1875 7.5 8.1875C6.02344 8.1875 4.82812 6.99219 4.82812 5.51562C4.82812 4.0625 6.02344 2.84375 7.5 2.84375Z" fill=""></path>
                          <path d="M14.555 13.25C12.6097 11.5859 10.1019 10.6719 7.50034 10.6719C4.89878 10.6719 2.39096 11.5859 0.445651 13.25C0.258151 13.3906 0.234714 13.6484 0.398776 13.8359C0.539401 14 0.797214 14.0234 0.984714 13.8828C2.7894 12.3594 5.10971 11.5156 7.52378 11.5156C9.93784 11.5156 12.2582 12.3594 14.0628 13.8828C14.1332 13.9531 14.2269 13.9766 14.3207 13.9766C14.4378 13.9766 14.555 13.9297 14.6253 13.8359C14.766 13.6484 14.7425 13.3906 14.555 13.25Z" fill=""></path>
                        </svg>
                        About
                      </a>
                      <a href="#" class="inline-flex items-center gap-2.5 border-y border-stroke px-2 py-1 font-medium text-black hover:border-primary hover:bg-primary hover:text-white dark:border-strokedark dark:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M7.17188 4.90625H3.23438C3 4.90625 2.8125 5.09375 2.8125 5.32813C2.8125 5.5625 3 5.75 3.23438 5.75H7.17188C7.40625 5.75 7.59375 5.5625 7.59375 5.32813C7.59375 5.09375 7.38281 4.90625 7.17188 4.90625Z" fill=""></path>
                          <path d="M3.23438 7.57813H5.03906C5.27344 7.57813 5.46094 7.39063 5.46094 7.15625C5.46094 6.92187 5.27344 6.73438 5.03906 6.73438H3.23438C3 6.73438 2.8125 6.92187 2.8125 7.15625C2.8125 7.39063 3 7.57813 3.23438 7.57813Z" fill=""></path>
                          <path d="M6.25781 8.60938H3.21094C2.97656 8.60938 2.78906 8.79688 2.78906 9.03125C2.78906 9.26563 2.97656 9.45313 3.21094 9.45313H6.25781C6.49219 9.45313 6.67969 9.26563 6.67969 9.03125C6.67969 8.79688 6.49219 8.60938 6.25781 8.60938Z" fill=""></path>
                          <path d="M11.3447 4.55466C10.8056 4.48435 10.3134 4.74216 10.0556 5.21091C9.9384 5.42185 10.0322 5.65622 10.2197 5.77341C10.4306 5.8906 10.665 5.79685 10.7822 5.60935C10.8759 5.44529 11.0634 5.35154 11.2509 5.37497C11.4618 5.39841 11.6259 5.56247 11.6493 5.74997C11.6728 5.93747 11.579 6.10154 11.415 6.17185C11.0634 6.33591 10.8056 6.75779 10.8056 7.15622V7.46091C10.8056 7.69529 10.9931 7.88279 11.2275 7.88279C11.4618 7.88279 11.6493 7.69529 11.6493 7.46091V7.15622C11.6493 7.08591 11.7431 6.94529 11.8134 6.92185C12.2822 6.68747 12.5634 6.19529 12.4931 5.65622C12.3993 5.07029 11.9306 4.62497 11.3447 4.55466Z" fill=""></path>
                          <path d="M11.2031 8.67969C10.8516 8.67969 10.5938 8.96094 10.5938 9.28906C10.5938 9.64062 10.875 9.89844 11.2031 9.89844C11.5547 9.89844 11.8125 9.61719 11.8125 9.28906C11.8359 8.96094 11.5547 8.67969 11.2031 8.67969Z" fill=""></path>
                          <path d="M12.9609 2.70312H2.03906C1.07813 2.70312 0.304688 3.47656 0.304688 4.4375V12.2422C0.304688 12.6172 0.492188 12.9453 0.820313 13.1328C0.984375 13.2266 1.14844 13.2734 1.33594 13.2734C1.52344 13.2734 1.6875 13.2266 1.85156 13.1328L4.57031 11.5625C4.59375 11.5391 4.64063 11.5391 4.66406 11.5391H12.9844C13.9453 11.5391 14.7188 10.7656 14.7188 9.80469V4.46094C14.7188 3.5 13.9219 2.70312 12.9609 2.70312ZM13.8984 9.82812C13.8984 10.3437 13.4766 10.7422 12.9844 10.7422H4.64063C4.45313 10.7422 4.28906 10.7891 4.125 10.8828L1.42969 12.4531C1.33594 12.5 1.24219 12.4766 1.21875 12.4531C1.19531 12.4297 1.125 12.3828 1.125 12.2656V4.46094C1.125 3.94531 1.54688 3.54688 2.03906 3.54688H12.9609C13.4766 3.54688 13.875 3.96875 13.875 4.46094V9.82812H13.8984Z" fill=""></path>
                        </svg>
                        Support
                      </a>
                      <a href="#" class="inline-flex items-center gap-2.5 border border-stroke px-2 py-1 font-medium text-black hover:border-primary hover:bg-primary hover:text-white dark:border-strokedark dark:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g clip-path="url(#clip0_652_20634)">
                            <path d="M12.5391 0.78125H11.3438C10.4063 0.78125 9.63281 1.55469 9.63281 2.49219V11.8906C9.63281 11.9609 9.65625 12.0313 9.70313 12.1016L11.4141 14.9141C11.5313 15.1016 11.7422 15.2188 11.9766 15.2188C12.2109 15.2188 12.4219 15.1016 12.5391 14.9141L14.25 12.1016C14.2969 12.0313 14.3203 11.9609 14.3203 11.8906V2.49219C14.25 1.55469 13.4766 0.78125 12.5391 0.78125ZM11.3438 1.60156H12.5391C13.0312 1.60156 13.4297 2 13.4297 2.49219V3.28906H10.4531V2.49219C10.4531 2 10.8516 1.60156 11.3438 1.60156ZM11.9297 14.2344L10.4297 11.7734V4.10938H13.4062V11.7734L11.9297 14.2344Z" fill=""></path>
                            <path d="M5.27344 0.804688H2.10938C1.35938 0.804688 0.75 1.41406 0.75 2.16406V13.8594C0.75 14.6094 1.35938 15.2188 2.10938 15.2188H5.27344C6.02344 15.2188 6.63281 14.6094 6.63281 13.8594V2.16406C6.60938 1.41406 6 0.804688 5.27344 0.804688ZM5.78906 13.8359C5.78906 14.1172 5.55469 14.375 5.25 14.375H2.10938C1.82813 14.375 1.57031 14.1406 1.57031 13.8359V12.8047C1.59375 12.8047 1.64062 12.8281 1.6875 12.8281H3.42188C3.65625 12.8281 3.84375 12.6406 3.84375 12.4063C3.84375 12.1719 3.65625 11.9844 3.42188 11.9844H1.6875C1.64062 11.9844 1.61719 11.9844 1.57031 12.0078V10.5078C1.59375 10.5078 1.64062 10.5313 1.6875 10.5313H2.20312C2.4375 10.5313 2.625 10.3438 2.625 10.1094C2.625 9.875 2.4375 9.6875 2.20312 9.6875H1.6875C1.64062 9.6875 1.61719 9.6875 1.57031 9.71094V8.23438C1.59375 8.23438 1.64062 8.25781 1.6875 8.25781H3.42188C3.65625 8.25781 3.84375 8.07031 3.84375 7.83594C3.84375 7.60156 3.65625 7.41406 3.42188 7.41406H1.6875C1.64062 7.41406 1.61719 7.41406 1.57031 7.4375V5.9375C1.59375 5.9375 1.64062 5.96094 1.6875 5.96094H2.20312C2.4375 5.96094 2.625 5.77344 2.625 5.53906C2.625 5.30469 2.4375 5.11719 2.20312 5.11719H1.6875C1.64062 5.11719 1.61719 5.11719 1.57031 5.14063V3.64063C1.59375 3.64063 1.64062 3.66406 1.6875 3.66406H3.42188C3.65625 3.66406 3.84375 3.47656 3.84375 3.24219C3.84375 3.00781 3.65625 2.84375 3.42188 2.84375H1.6875C1.64062 2.84375 1.61719 2.84375 1.57031 2.86719V2.16406C1.57031 1.88281 1.80469 1.625 2.10938 1.625H5.27344C5.55469 1.625 5.8125 1.85938 5.8125 2.16406V13.8359H5.78906Z" fill=""></path>
                          </g>
                          <defs>
                            <clipPath id="clip0_652_20634">
                              <rect width="15" height="15" fill="white" transform="translate(0 0.5)"></rect>
                            </clipPath>
                          </defs>
                        </svg>
                        Services
                      </a>
					  <a href="#" class="inline-flex items-center gap-2.5 border border-primary bg-primary px-2 py-1 font-medium text-white hover:border-primary hover:bg-primary hover:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M7.5 9.03125C9.42187 9.03125 10.9922 7.46094 10.9922 5.53906C10.9922 3.61719 9.42187 2.02344 7.5 2.02344C5.57812 2.02344 4.00781 3.59375 4.00781 5.51562C4.00781 7.4375 5.57812 9.03125 7.5 9.03125ZM7.5 2.84375C8.97656 2.84375 10.1719 4.03906 10.1719 5.51562C10.1719 6.99219 8.97656 8.1875 7.5 8.1875C6.02344 8.1875 4.82812 6.99219 4.82812 5.51562C4.82812 4.0625 6.02344 2.84375 7.5 2.84375Z" fill=""></path>
                          <path d="M14.555 13.25C12.6097 11.5859 10.1019 10.6719 7.50034 10.6719C4.89878 10.6719 2.39096 11.5859 0.445651 13.25C0.258151 13.3906 0.234714 13.6484 0.398776 13.8359C0.539401 14 0.797214 14.0234 0.984714 13.8828C2.7894 12.3594 5.10971 11.5156 7.52378 11.5156C9.93784 11.5156 12.2582 12.3594 14.0628 13.8828C14.1332 13.9531 14.2269 13.9766 14.3207 13.9766C14.4378 13.9766 14.555 13.9297 14.6253 13.8359C14.766 13.6484 14.7425 13.3906 14.555 13.25Z" fill=""></path>
                        </svg>
                        About
                      </a>
                      <a href="#" class="inline-flex items-center gap-2.5 border-y border-stroke px-2 py-1 font-medium text-black hover:border-primary hover:bg-primary hover:text-white dark:border-strokedark dark:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M7.17188 4.90625H3.23438C3 4.90625 2.8125 5.09375 2.8125 5.32813C2.8125 5.5625 3 5.75 3.23438 5.75H7.17188C7.40625 5.75 7.59375 5.5625 7.59375 5.32813C7.59375 5.09375 7.38281 4.90625 7.17188 4.90625Z" fill=""></path>
                          <path d="M3.23438 7.57813H5.03906C5.27344 7.57813 5.46094 7.39063 5.46094 7.15625C5.46094 6.92187 5.27344 6.73438 5.03906 6.73438H3.23438C3 6.73438 2.8125 6.92187 2.8125 7.15625C2.8125 7.39063 3 7.57813 3.23438 7.57813Z" fill=""></path>
                          <path d="M6.25781 8.60938H3.21094C2.97656 8.60938 2.78906 8.79688 2.78906 9.03125C2.78906 9.26563 2.97656 9.45313 3.21094 9.45313H6.25781C6.49219 9.45313 6.67969 9.26563 6.67969 9.03125C6.67969 8.79688 6.49219 8.60938 6.25781 8.60938Z" fill=""></path>
                          <path d="M11.3447 4.55466C10.8056 4.48435 10.3134 4.74216 10.0556 5.21091C9.9384 5.42185 10.0322 5.65622 10.2197 5.77341C10.4306 5.8906 10.665 5.79685 10.7822 5.60935C10.8759 5.44529 11.0634 5.35154 11.2509 5.37497C11.4618 5.39841 11.6259 5.56247 11.6493 5.74997C11.6728 5.93747 11.579 6.10154 11.415 6.17185C11.0634 6.33591 10.8056 6.75779 10.8056 7.15622V7.46091C10.8056 7.69529 10.9931 7.88279 11.2275 7.88279C11.4618 7.88279 11.6493 7.69529 11.6493 7.46091V7.15622C11.6493 7.08591 11.7431 6.94529 11.8134 6.92185C12.2822 6.68747 12.5634 6.19529 12.4931 5.65622C12.3993 5.07029 11.9306 4.62497 11.3447 4.55466Z" fill=""></path>
                          <path d="M11.2031 8.67969C10.8516 8.67969 10.5938 8.96094 10.5938 9.28906C10.5938 9.64062 10.875 9.89844 11.2031 9.89844C11.5547 9.89844 11.8125 9.61719 11.8125 9.28906C11.8359 8.96094 11.5547 8.67969 11.2031 8.67969Z" fill=""></path>
                          <path d="M12.9609 2.70312H2.03906C1.07813 2.70312 0.304688 3.47656 0.304688 4.4375V12.2422C0.304688 12.6172 0.492188 12.9453 0.820313 13.1328C0.984375 13.2266 1.14844 13.2734 1.33594 13.2734C1.52344 13.2734 1.6875 13.2266 1.85156 13.1328L4.57031 11.5625C4.59375 11.5391 4.64063 11.5391 4.66406 11.5391H12.9844C13.9453 11.5391 14.7188 10.7656 14.7188 9.80469V4.46094C14.7188 3.5 13.9219 2.70312 12.9609 2.70312ZM13.8984 9.82812C13.8984 10.3437 13.4766 10.7422 12.9844 10.7422H4.64063C4.45313 10.7422 4.28906 10.7891 4.125 10.8828L1.42969 12.4531C1.33594 12.5 1.24219 12.4766 1.21875 12.4531C1.19531 12.4297 1.125 12.3828 1.125 12.2656V4.46094C1.125 3.94531 1.54688 3.54688 2.03906 3.54688H12.9609C13.4766 3.54688 13.875 3.96875 13.875 4.46094V9.82812H13.8984Z" fill=""></path>
                        </svg>
                        Support
                      </a>
                      <a href="#" class="inline-flex items-center gap-2.5 border border-stroke px-2 py-1 font-medium text-black hover:border-primary hover:bg-primary hover:text-white dark:border-strokedark dark:text-white dark:hover:border-primary sm:px-6 sm:py-3">
                        <svg class="fill-current" width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g clip-path="url(#clip0_652_20634)">
                            <path d="M12.5391 0.78125H11.3438C10.4063 0.78125 9.63281 1.55469 9.63281 2.49219V11.8906C9.63281 11.9609 9.65625 12.0313 9.70313 12.1016L11.4141 14.9141C11.5313 15.1016 11.7422 15.2188 11.9766 15.2188C12.2109 15.2188 12.4219 15.1016 12.5391 14.9141L14.25 12.1016C14.2969 12.0313 14.3203 11.9609 14.3203 11.8906V2.49219C14.25 1.55469 13.4766 0.78125 12.5391 0.78125ZM11.3438 1.60156H12.5391C13.0312 1.60156 13.4297 2 13.4297 2.49219V3.28906H10.4531V2.49219C10.4531 2 10.8516 1.60156 11.3438 1.60156ZM11.9297 14.2344L10.4297 11.7734V4.10938H13.4062V11.7734L11.9297 14.2344Z" fill=""></path>
                            <path d="M5.27344 0.804688H2.10938C1.35938 0.804688 0.75 1.41406 0.75 2.16406V13.8594C0.75 14.6094 1.35938 15.2188 2.10938 15.2188H5.27344C6.02344 15.2188 6.63281 14.6094 6.63281 13.8594V2.16406C6.60938 1.41406 6 0.804688 5.27344 0.804688ZM5.78906 13.8359C5.78906 14.1172 5.55469 14.375 5.25 14.375H2.10938C1.82813 14.375 1.57031 14.1406 1.57031 13.8359V12.8047C1.59375 12.8047 1.64062 12.8281 1.6875 12.8281H3.42188C3.65625 12.8281 3.84375 12.6406 3.84375 12.4063C3.84375 12.1719 3.65625 11.9844 3.42188 11.9844H1.6875C1.64062 11.9844 1.61719 11.9844 1.57031 12.0078V10.5078C1.59375 10.5078 1.64062 10.5313 1.6875 10.5313H2.20312C2.4375 10.5313 2.625 10.3438 2.625 10.1094C2.625 9.875 2.4375 9.6875 2.20312 9.6875H1.6875C1.64062 9.6875 1.61719 9.6875 1.57031 9.71094V8.23438C1.59375 8.23438 1.64062 8.25781 1.6875 8.25781H3.42188C3.65625 8.25781 3.84375 8.07031 3.84375 7.83594C3.84375 7.60156 3.65625 7.41406 3.42188 7.41406H1.6875C1.64062 7.41406 1.61719 7.41406 1.57031 7.4375V5.9375C1.59375 5.9375 1.64062 5.96094 1.6875 5.96094H2.20312C2.4375 5.96094 2.625 5.77344 2.625 5.53906C2.625 5.30469 2.4375 5.11719 2.20312 5.11719H1.6875C1.64062 5.11719 1.61719 5.11719 1.57031 5.14063V3.64063C1.59375 3.64063 1.64062 3.66406 1.6875 3.66406H3.42188C3.65625 3.66406 3.84375 3.47656 3.84375 3.24219C3.84375 3.00781 3.65625 2.84375 3.42188 2.84375H1.6875C1.64062 2.84375 1.61719 2.84375 1.57031 2.86719V2.16406C1.57031 1.88281 1.80469 1.625 2.10938 1.625H5.27344C5.55469 1.625 5.8125 1.85938 5.8125 2.16406V13.8359H5.78906Z" fill=""></path>
                          </g>
                          <defs>
                            <clipPath id="clip0_652_20634">
                              <rect width="15" height="15" fill="white" transform="translate(0 0.5)"></rect>
                            </clipPath>
                          </defs>
                        </svg>
                        Services
                      </a>
                    		</div>
						</div>
					</div>
				</div>
				<!-- ====== Button End -->

	            <!-- ====== Chart Thirteen Start -->
	            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-7">
					<div class="mb-5.5 flex flex-wrap items-center justify-between gap-2">
					    <div>
					      <h4 class="text-title-sm2 font-bold text-black dark:text-white">
					        Clients Interaction
					      </h4>
					    </div>
					    <div class="relative z-20 inline-block rounded">
					      <select
					        class="relative z-20 inline-flex appearance-none rounded border border-stroke bg-transparent py-[5px] pl-3 pr-8 text-sm font-medium outline-none dark:border-strokedark"
					      >
					        <option value="" class="dark:bg-boxdark">Last 7 days</option>
					        <option value="" class="dark:bg-boxdark">Last 15 days</option>
					      </select>
					      <span class="absolute right-3 top-1/2 z-10 -translate-y-1/2">
					        <svg
					          width="17"
					          height="17"
					          viewBox="0 0 17 17"
					          fill="none"
					          xmlns="http://www.w3.org/2000/svg"
					        >
					          <path
					            d="M8.61025 11.8872C8.46025 11.8872 8.33525 11.8372 8.21025 11.7372L2.46025 6.08723C2.23525 5.86223 2.23525 5.51223 2.46025 5.28723C2.68525 5.06223 3.03525 5.06223 3.26025 5.28723L8.61025 10.5122L13.9603 5.23723C14.1853 5.01223 14.5353 5.01223 14.7603 5.23723C14.9853 5.46223 14.9853 5.81223 14.7603 6.03723L9.01025 11.6872C8.88525 11.8122 8.76025 11.8872 8.61025 11.8872Z"
					            fill="#64748B"
					          />
					        </svg>
					      </span>
					    </div>
					</div>

					<div class="mb-3 flex flex-wrap gap-6">
					    <div>
					      <p class="mb-1.5 text-sm font-medium">Invested Value</p>
					      <div class="flex items-center gap-2.5">
					        <p class="font-medium text-black dark:text-white">$1,279.95</p>
					        <p class="flex items-center gap-1 font-medium text-meta-3">
					          1,22%

					          <svg
					            class="fill-current"
					            width="11"
					            height="8"
					            viewBox="0 0 11 8"
					            fill="none"
					            xmlns="http://www.w3.org/2000/svg"
					          >
					            <path
					              d="M5.77105 0.0465078L10.7749 7.54651L0.767256 7.54651L5.77105 0.0465078Z"
					              fill=""
					            />
					          </svg>
					        </p>
					      </div>
					    </div>

					    <div>
					      <p class="mb-1.5 text-sm font-medium">Total Returns</p>
					      <div class="flex items-center gap-2.5">
					        <p class="font-medium text-black dark:text-white">$22,543.87</p>
					        <p class="flex items-center gap-1 font-medium text-meta-3">
					          10.14%

					          <svg
					            class="fill-current"
					            width="11"
					            height="8"
					            viewBox="0 0 11 8"
					            fill="none"
					            xmlns="http://www.w3.org/2000/svg"
					          >
					            <path
					              d="M5.77105 0.0465078L10.7749 7.54651L0.767256 7.54651L5.77105 0.0465078Z"
					              fill=""
					            />
					          </svg>
					        </p>
					      </div>
					    </div>
					</div>
					<div>
					    <div id="chartThirteen" class="-ml-5"></div>
					</div>
				</div>
	            <!-- ====== Chart Thirteen End -->

	            <!-- ====== Latest Transaction End -->
				<div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-8">
				  <div class="flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
				    <div class="flex w-full flex-wrap gap-3 sm:gap-5">
				      <div class="flex min-w-47.5">
				        <span
				          class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-primary"
				        >
				          <span
				            class="block h-2.5 w-full max-w-2.5 rounded-full bg-primary"
				          ></span>
				        </span>
				        <div class="w-full">
				          <p class="font-semibold text-primary">Total Revenue</p>
				          <p class="text-sm font-medium">12.04.2022 - 12.05.2022</p>
				        </div>
				      </div>
				      <div class="flex min-w-47.5">
				        <span
				          class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-secondary"
				        >
				          <span
				            class="block h-2.5 w-full max-w-2.5 rounded-full bg-secondary"
				          ></span>
				        </span>
				        <div class="w-full">
				          <p class="font-semibold text-secondary">Total Sales</p>
				          <p class="text-sm font-medium">12.04.2022 - 12.05.2022</p>
				        </div>
				      </div>
				    </div>
				    <div class="flex w-full max-w-45 justify-end">
				      <div
				        class="inline-flex items-center rounded-md bg-whiter p-1.5 dark:bg-meta-4"
				      >
				        <button
				          class="rounded bg-white px-3 py-1 text-xs font-medium text-black shadow-card hover:bg-white hover:shadow-card dark:bg-boxdark dark:text-white dark:hover:bg-boxdark"
				        >
				          Day
				        </button>
				        <button
				          class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-boxdark"
				        >
				          Week
				        </button>
				        <button
				          class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card dark:text-white dark:hover:bg-boxdark"
				        >
				          Month
				        </button>
				      </div>
				    </div>
				  </div>
				  <div>
				    <div id="chartOne" class="-ml-5"></div>
				  </div>
				</div>

		              <!-- ====== Latest Transaction Start -->
		        <div class="col-span-12 rounded-sm border border-stroke bg-white p-5 shadow-default dark:border-strokedark dark:bg-boxdark sm:p-7.5 xl:col-span-7">
		               	<div class="mb-10 flex flex-wrap items-center justify-between gap-2">
		                  <div>
		                    <h4
		                      class="text-title-sm2 font-bold text-black dark:text-white"
		                    >
		                      Latest Transaction
		                    </h4>
		                  </div>
		                  <div class="relative z-20 inline-block rounded">
		                    <select
		                      class="relative z-20 inline-flex appearance-none rounded border border-stroke bg-transparent py-[5px] pl-3 pr-8 text-sm font-medium outline-none dark:border-strokedark"
		                    >
		                      <option value="" class="dark:bg-boxdark">
		                        Last 7 days
		                      </option>
		                      <option value="" class="dark:bg-boxdark">
		                        Last 15 days
		                      </option>
		                    </select>
		                    <span
		                      class="absolute right-3 top-1/2 z-10 -translate-y-1/2"
		                    >
		                      <svg
		                        width="17"
		                        height="17"
		                        viewBox="0 0 17 17"
		                        fill="none"
		                        xmlns="http://www.w3.org/2000/svg"
		                      >
		                        <path
		                          d="M8.61025 11.8872C8.46025 11.8872 8.33525 11.8372 8.21025 11.7372L2.46025 6.08723C2.23525 5.86223 2.23525 5.51223 2.46025 5.28723C2.68525 5.06223 3.03525 5.06223 3.26025 5.28723L8.61025 10.5122L13.9603 5.23723C14.1853 5.01223 14.5353 5.01223 14.7603 5.23723C14.9853 5.46223 14.9853 5.81223 14.7603 6.03723L9.01025 11.6872C8.88525 11.8122 8.76025 11.8872 8.61025 11.8872Z"
		                          fill="#64748B"
		                        />
		                      </svg>
		                    </span>
		                  </div>
		                </div>

		                <div class="flex flex-col gap-[25px]">
		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-15.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          Apple Inc.
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">3.8%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-meta-3"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 0.450195L8.11168 6.4502H0.1056L4.10864 0.450195Z"
		                            fill=""
		                          />
		                        </svg>

		                        3.69%
		                      </p>
		                      <p class="text-xs">Ratio</p>
		                    </div>

		                    <div class="text-right sm:col-span-2">
		                      <h5 class="font-bold text-black dark:text-white">
		                        + $9346.00
		                      </h5>
		                      <p class="text-xs font-medium">20 Sep, 27</p>
		                    </div>
		                  </div>
		                  <!-- Transaction item end -->

		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-17.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          Amazon
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">2.7%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-meta-3"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 0.450195L8.11168 6.4502H0.1056L4.10864 0.450195Z"
		                            fill=""
		                          />
		                        </svg>

		                        3.69%
		                      </p>
		                      <p class="text-xs">Ratio</p>
		                    </div>

		                    <div class="text-right sm:col-span-2">
		                      <h5 class="font-bold text-black dark:text-white">
		                        + $6879.00
		                      </h5>
		                      <p class="text-xs font-medium">20 Sep, 27</p>
		                    </div>
		                  </div>
		                  <!-- Transaction item end -->

		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-14.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          Netflix
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">2.5%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-red"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 6.4502L0.105604 0.450196L8.11168 0.450195L4.10864 6.4502Z"
		                            fill=""
		                          />
		                        </svg>

		                        3.69%
		                      </p>
		                      <p class="text-xs">Ratio</p>
		                    </div>

		                    <div class="text-right sm:col-span-2">
		                      <h5 class="font-bold text-black dark:text-white">
		                        - $1439.00
		                      </h5>
		                      <p class="text-xs font-medium">20 Sep, 27</p>
		                    </div>
		                  </div>
		                  <!-- Transaction item end -->

		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-18.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          IBM
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">1.8%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-red"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 6.4502L0.105604 0.450196L8.11168 0.450195L4.10864 6.4502Z"
		                            fill=""
		                          />
		                        </svg>

		                        3.69%
		                      </p>
		                      <p class="text-xs">Ratio</p>
		                    </div>

		                    <div class="text-right sm:col-span-2">
		                      <h5 class="font-bold text-black dark:text-white">
		                        - $2329.00
		                      </h5>
		                      <p class="text-xs font-medium">20 Sep, 27</p>
		                    </div>
		                  </div>
		                  <!-- Transaction item end -->

		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-16.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          Meta
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">3.7%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-meta-3"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 0.450195L8.11168 6.4502H0.1056L4.10864 0.450195Z"
		                            fill=""
		                          />
		                        </svg>

		                        3.69%
		                      </p>
		                      <p class="text-xs">Ratio</p>
		                    </div>

		                    <div class="text-right sm:col-span-2">
		                      <h5 class="font-bold text-black dark:text-white">
		                        + $1026.00
		                      </h5>
		                      <p class="text-xs font-medium">20 Sep, 27</p>
		                    </div>
		                  </div>
		                  <!-- Transaction item end -->

		                  <!-- Transaction item start -->
		                  <div
		                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
		                  >
		                    <div
		                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
		                    >
		                      <div
		                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
		                      >
		                        <img src="src/images/brand/brand-19.svg" alt="brand" />
		                      </div>

		                      <div class="hidden sm:block">
		                        <h5 class="font-bold text-black dark:text-white">
		                          Microsoft
		                        </h5>
		                        <p class="text-xs font-medium">Buy Stock</p>
		                      </div>
		                    </div>

		                    <div class="hidden sm:col-span-2 sm:block">
		                      <h5 class="font-bold text-black dark:text-white">
		                        Interest rate
		                      </h5>
		                      <p class="text-xs font-medium">3.7%</p>
		                    </div>

		                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
		                      <p
		                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-meta-3"
		                      >
		                        <svg
		                          class="fill-current"
		                          width="9"
		                          height="7"
		                          viewBox="0 0 9 7"
		                          fill="none"
		                          xmlns="http://www.w3.org/2000/svg"
		                        >
		                          <path
		                            d="M4.10864 0.450195L8.11168 6.4502H0.1056L4.10864 0.450195Z"
		                            fill=""
		                          />
		                        </svg>

	                        3.69%
	                      </p>
	                      <p class="text-xs">Ratio</p>
	                    </div>

	                    <div class="text-right sm:col-span-2">
	                      <h5 class="font-bold text-black dark:text-white">
	                        + $3226.00
	                      </h5>
	                      <p class="text-xs font-medium">20 Sep, 27</p>
	                    </div>
	                  </div>
	                  <!-- Transaction item end -->

	                  <!-- Transaction item start -->
	                  <div
	                    class="grid grid-cols-2 items-center gap-5 xsm:grid-cols-3 sm:grid-cols-8"
	                  >
	                    <div
	                      class="flex items-center gap-4.5 sm:col-span-3 2xl:col-span-2"
	                    >
	                      <div
	                        class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-[#EEF2F8]"
	                      >
	                        <img src="src/images/brand/brand-20.svg" alt="brand" />
	                      </div>

	                      <div class="hidden sm:block">
	                        <h5 class="font-bold text-black dark:text-white">
	                          Tesla
	                        </h5>
	                        <p class="text-xs font-medium">Buy Stock</p>
	                      </div>
	                    </div>

	                    <div class="hidden sm:col-span-2 sm:block">
	                      <h5 class="font-bold text-black dark:text-white">
	                        Interest rate
	                      </h5>
	                      <p class="text-xs font-medium">3.7%</p>
	                    </div>

	                    <div class="hidden xsm:block sm:col-span-1 2xl:col-span-2">
	                      <p
	                        class="mb-0.5 flex items-center gap-[5px] text-sm font-bold leading-6 text-red"
	                      >
	                        <svg
	                          class="fill-current"
	                          width="9"
	                          height="7"
	                          viewBox="0 0 9 7"
	                          fill="none"
	                          xmlns="http://www.w3.org/2000/svg"
	                        >
	                          <path
	                            d="M4.10864 6.4502L0.105604 0.450196L8.11168 0.450195L4.10864 6.4502Z"
	                            fill=""
	                          />
	                        </svg>

	                        1.24%
	                      </p>
	                      <p class="text-xs">Ratio</p>
	                    </div>

	                    <div class="text-right sm:col-span-2">
	                      <h5 class="font-bold text-black dark:text-white">
	                        - $6426.00
	                      </h5>
	                      <p class="text-xs font-medium">20 Sep, 27</p>
	                    </div>
	                  </div>
	                  <!-- Transaction item end -->
	               	</div>
	            </div>

	            

            </div>
          </div>
        </main>
        <!-- ===== Main Content End ===== -->
      </div>
      <!-- ===== Content Area End ===== -->
    </div>
    <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>

</html>
