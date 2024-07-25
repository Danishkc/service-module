
<script setup>
import { ref } from 'vue';
import {Link} from '@inertiajs/vue3';
var props =defineProps(['menu','submenu','main','sub','dt']);
let main_id =  ref(props.main);
let sub_id =  ref(props.sub);
let click_mobile_menu =  ref('');
let menuclickicononlyvalue =  ref('sidebar-icon-only');
let submenueach =  ref([]);
function click_main(id)
{
    if( main_id.value == id)
    {
        main_id.value = 0;
    }else{
        main_id.value = id;
    }

}
function menuclick()
{
  if(click_mobile_menu.value == 'active')
  {
     click_mobile_menu.value = '';
  }else{
     click_mobile_menu.value = 'active';
  }

}
function menuclickicononly()
{
  if(menuclickicononlyvalue.value == 'sidebar-icon-only')
  {
    menuclickicononlyvalue.value = '';
  }else{
    menuclickicononlyvalue.value = 'sidebar-icon-only';
  }

}
function getsubmenu(id)
{
  return  submenueach.value = props.submenu.filter(v => v.sub_id == id);
}

</script>
<template>


    <div :class="'container-scroller '+ menuclickicononlyvalue">
    <!-- partial:../../partials/_navbar.html -->
  <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="#"><img src="/asset/images/logo.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="#"><img src="/asset/images/logo.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" @click="menuclickicononly()" type="button" >
          <span class="ti-layout-grid2"></span>
        </button>
        <ul class="navbar-nav mr-lg-2" hidden="">
          <li class="nav-item nav-search d-none d-lg-block" hidden="">
            <div class="input-group" hidden="">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="ti-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
             <!-- <img :src="'/upload/user/'+dt['photo']" alt="profile"/> -->

            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="/logout">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
              <Link class="dropdown-item" :href="'/pass'">
                <i class="ti-key text-primary"></i>
                Change Password
              </Link>
            </div>
          </li>
          <!-- <li class="nav-item nav-settings d-none d-lg-flex" hidden="">
            <a class="nav-link" href="#">
              <i class="ti-more"></i>
            </a>
          </li> -->
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" @click="menuclick()">
          <span class="ti-layout-grid2"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->


      <!-- partial -->   <nav :class="'sidebar sidebar-offcanvas '+click_mobile_menu " id="sidebar">
        <ul class="nav">
                         <li class="nav-item">
                                          <li class="nav-item">
                                            <Link class="nav-link" href="/dashboard"> <i class="ti-home menu-icon"></i>
                              <span class="menu-title">Dashboard</span></Link>


                        </li>

                                   </li>
                         <li class="nav-item" v-for="m_item in menu" :key="m_item.id">
                                         <a @click="click_main(m_item.id)" class="nav-link" :aria-expanded="m_item.id == main_id ? true : false" >
                           <i :class="m_item.icon +' menu-icon'"></i>
                            <span class="menu-title">{{ m_item.name }}</span>
                            <i class="menu-arrow"></i>
                        </a>
                                              <div :class="'collapse '+ (m_item.id == main_id ? 'show' : '') " >
                                <ul class="nav flex-column sub-menu">
                                                                            <li class="nav-item"  v-for="sub_item in getsubmenu(m_item.id)" :key="sub_item.id">
                                                                                <Link class="nav-link"  :class="'nav-link ' + (sub_item.id == sub ? 'active' : '')"  :href="'/'+sub_item.url">{{ sub_item.name }}</Link>
                                       </li>
                                                                            </ul>
                            </div>
                                    </li>







        </ul>
      </nav>
 <div class="main-panel">
       <div class="content-wrapper">
              <div class="content col-12">

                    <!-- alert message -->


                <slot/>



                </div> <!-- /.content -->
                 </div>


       <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Developed by <a href="https://www.rpos.in/" target="_blank">RPOS</a> in india </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Ver. 1.0.1 </span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
</template>

<style scoped>
.navbar-toggler:focus, .navbar-toggler:active {
  outline: none;
  box-shadow: none;
}
</style>
