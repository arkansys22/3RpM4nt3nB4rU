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
