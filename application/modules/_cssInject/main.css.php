<style type="text/css">
  .tab-wizard {
    width: 100%;
    padding-right: 20px;
  }

  .tab-wizard-badge {
    min-width: 180px;
    height: 40px;
    background-color: #f3f3f3;
    padding: 10px 15px;
    position: relative;
    box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .15);
    border-radius: 2px 0px 0px 2px;
  }

  .tab-wizard-badge:before {
    content: "";
    position: absolute;
    right: -20px;
    bottom: 0;
    width: 0;
    height: 0;
    border-left: 20px solid #f3f3f3;
    border-top: 20px solid transparent;
    border-bottom: 20px solid transparent;
  }

  .tab-wizard-badge-active {
    background-color: #00BCD4;
  }

  .tab-wizard-badge-active:before {
    border-left: 20px solid #00BCD4;
  }

  .tab-wizard-badge-finish {
    background-color: #32c787;
  }

  .tab-wizard-badge-finish:before {
    border-left: 20px solid #32c787;
  }

  .tab-wizard-badge label {
    color: #666;
    margin-bottom: 0;
  }

  .tab-wizard-badge-active label {
    color: #f2f2f2;
    font-weight: 500;
  }

  .tab-wizard-badge-finish label {
    color: #f2f2f2;
    font-weight: 500;
  }

  .tab-wizard-line {
    width: 4px;
    height: 10px;
    background-color: #f3f3f3;
    margin: 0 auto;
    margin-top: .2rem;
    margin-bottom: .2rem;
    box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .15);
  }

  .tab-wizard-line-active {
    background-color: #00BCD4;
  }

  .btn-custom {
    padding-left: 30px;
    padding-right: 30px;
  }

  .buttons-container {
    background-color: #f9f9f9;
    box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .15);
    padding: 15px;
    margin-top: 1rem;
  }

  .list-group-item {
    padding: .5rem 1rem;
    border: 1px solid rgba(0, 0, 0, .10);
  }

  .clear {
    height: 20px;
  }

  .clear-sm {
    height: 10px;
  }

  .pull-right {
    float: right;
  }

  .card-header-custom {
    padding: 2.1rem 1rem;
    padding-top: 1.3rem;
    padding-bottom: 1.3rem;
  }

  .card-body-custom {
    padding: 0 1rem;
  }

  .dataTables_wrapper .table {
    margin: 10px 0 20px;
  }

  .col-2-custom {
    max-width: 10.6666666667%;
    padding-left: 0;
  }

  /* Preview */
  .float-right {
    float: right;
  }

  .preview-po {
    background: #fff;
    border: 10px solid #f1f1f1;
    padding: 20px;
    border-radius: 2px;
  }

  .preview-po .preview-header .logo {
    height: 80px;
  }

  .preview-po .preview-header .table-header {
    width: 100%;
  }

  .preview-po .preview-header .table-header .kop-surat h5 {
    font-size: 14px;
    margin-bottom: .3rem;
  }

  .preview-po .preview-header .table-header .kop-surat span {
    font-size: 11px;
    color: #333;
  }

  .preview-po .preview-header .double-line {
    border-top: 3px double #8c8b8b;
    margin-top: 20px;
    margin-bottom: 30px;
  }

  .preview-po .preview-body .table-body {
    border: 1px solid #8c8b8b;
    font-size: 12px;
  }

  .preview-po .preview-body .table-body .th {
    background: #f9f9f9;
    padding: 4px 8px;
    border-bottom: 1px solid #8c8b8b;
  }

  .preview-po .preview-body .table-body .td {
    background: #fff;
    padding: 4px 8px;
    border-bottom: 1px solid #8c8b8b;
  }

  .preview-po .preview-body .table-body-right {
    font-size: 12px;
  }

  .preview-po .preview-body .table-body-right .th {
    padding: 4px 8px;
  }

  .preview-po .preview-body .table-body-right .td {
    padding: 4px 8px;
  }

  .preview-po .preview-body .table-order-item {
    width: 100%;
    margin-bottom: 20px;
    font-size: 12px;
  }

  .preview-po .preview-body .table-order-item th {
    border: 1px solid #8c8b8b;
    text-align: center;
    padding: 4px 8px;
  }

  .preview-po .preview-body .table-order-item td {
    border: 1px solid #8c8b8b;
    padding: 4px 8px;
  }

  .preview-po .preview-body .table-order-item .no-border {
    border: 0;
  }

  .preview-po .preview-body .table-order-item .ttd {
    height: 60px;
    width: 14.285%;
  }

  .preview-po .preview-body .table-information {
    font-size: 10px;
  }

  .preview-po .preview-body .table-information td {
    padding: 0px 10px 0px 0px;
  }

  /* END ## Preview */

  .approval-history .current-status {
    margin-bottom: 30px;
  }

  .approval-history .current-status p {
    line-height: .3rem;
  }

  input[type="text"]:disabled {
    background-color: transparent;
  }

  .approval-form {
    background: #f9f9f9;
    padding: 10px 15px;
    box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .15);
    border-top: 4px solid #32c787;
    border-radius: 8px 8px 2px 2px;
  }

  .row-group {
    background-color: #f9f9f9 !important;
  }

  .attachment {
    margin-top: 1rem;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 2px;
  }

  .attachment-item {
    margin-bottom: 1rem;
  }

  .attachment-preview {
    width: 48px;
    height: 48px;
    border-radius: 2px;
  }

  .attachment-preview-img {
    object-fit: cover;
  }

  .attachment-preview-file {
    background-color: #607D8B;
    color: #f9f9f9;
    font-size: 15pt;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .attachment-size {
    color: #888;
    font-size: 10px;
  }

  .link-black {
    color: #777;
  }

  .link-black:hover {
    color: #444;
  }

  .cx__form-control {
    display: block;
    width: 100%;
    padding: 8px 10px;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1;
    color: #69707a;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #c5ccd6;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .cx__filter-label {
    height: 34.13px;
    background: #f2f2f2;
  }

  .cx__filter-combo-wrapper {
    height: 34.13px;
    border: 1px solid #c5ccd6;
    padding-left: 4px;
    padding-right: 10px;
  }

  .cx__filter-combo {
    height: 32px;
    border: 0;
    padding-left: 4px;
    padding-right: 4px;
  }

  .cx__filter-combo-wrapper .select2-container--default .select2-selection--single {
    height: 31.13px;
    border: 0;
    padding: unset;
    padding-left: 5px;
    padding-right: 5px;
  }

  .cx__filter-textfield {
    height: 34.13px;
    border: 1px solid #c5ccd6;
    border-radius: 0;
    padding-left: 10px;
    padding-right: 10px;
  }

  .cx__filter-submit {
    height: 34.13px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>