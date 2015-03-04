<header class="header header-fixed navbar">

  <div class="brand">
    <!-- toggle offscreen menu -->
    <a href="javascript:;" class="ti-menu navbar-toggle off-left visible-xs"
       data-toggle="collapse"
       data-target="#hor-menu">
    </a>
    <!-- /toggle offscreen menu -->

    <!-- logo -->
    <a href="<?php echo STATIC_ROOT . 'admin_finance/home/' ?>" class="navbar-brand">
      <img src="<?php echo STATIC_ROOT . 'img/logo.png' ?>" alt=""/>
      <span class="heading-font">easyVarsity</span>
    </a>
    <!-- /logo -->
  </div>

  <div class="collapse navbar-collapse pull-left" id="hor-menu">
    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo STATIC_ROOT . 'admin_finance/define-fees/' ?>">
          <span>Define Fees</span>
        </a>
      </li>

      <li>
        <a href="<?php echo STATIC_ROOT . 'admin_finance/payment-reg/' ?>">
          <span>Register Payments</span>
        </a>
      </li>

      <li>
        <a href="<?php echo STATIC_ROOT . 'admin_finance/debtors/' ?>">
          <span>View Debtors</span>
        </a>
      </li>

      <li>
        <a href="<?php echo STATIC_ROOT . 'admin_finance/payment-history/' ?>">
          <span>Look Up Payment History</span>
        </a>
      </li>

      <li>
        <a href="<?php echo STATIC_ROOT . 'admin_finance/income.php' ?>">
          <span>View Income per Session</span>
        </a>
      </li>
    </ul>
  </div>

  <ul class="nav navbar-nav navbar-right">


    <li class="off-right">
      <a href="<?php echo STATIC_ROOT . 'admin_finance/login/logout.php' ?>">
        Signout </a>
    </li>


  </ul>
  </li>
  </ul>
</header>