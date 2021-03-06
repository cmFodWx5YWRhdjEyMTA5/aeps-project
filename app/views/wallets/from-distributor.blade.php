<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
  <div ng-controller="WalletRequestCtrl" class="head-weight">
      
      <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                  <div class="panel-heading panel-heading-divider">Wallet Credit Request Form</div>
                <div class="panel-body table-responsive">
                  <form class="form-signin" name="balanceRequestFrm" ng-submit="submit(balanceRequest)" novalidate>
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Distributor</small></label>
                          <input type="text" ng-model="balanceRequest.distributor" class="form-control" disabled name="branch" placeholder="Distributor" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Amount<font color="red"> * (Required)</font></small></label>
                          <input type="text" ng-model="balanceRequest.amount" class="form-control" name="amount" placeholder="ENTER AMOUNT" required isnumber>
                          <p ng-show="balanceRequestFrm.$submitted && (balanceRequestFrm.amount.$invalid || invalidAmount(balanceRequest.amount))" class="err-mark">Please enter an amount between 100 and 2500000.</p>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label"><small>Remarks</small></label>
                          <input type="text" class="form-control" ng-model="balanceRequest.remarks" name="remarks" placeholder="REMARKS">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" name="btn-credit-request"><small><i class="glyphicon glyphicon-ok"></i>&nbsp;SUBMIT REQUEST</small></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
      <!-- End Row -->
    <!--<div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          
          
          
          
        </div>
      </div>
    </div> Hidden by PR : Removable Markup -->
  </div>
@stop

@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('WalletRequestCtrl', ['$scope', '$http', function ($scope, $http) {

      //window.s = $scope;
      $scope.userType = {{$parent}};
      $scope.balanceRequest = {
        distributor : $scope.userType.name,
        parent_id : $scope.userType.id
      }
      $scope.modeOfTransferDictS = {{ json_encode(Config::get('dictionary.MODE_OF_TRANSFER')) }};
      $scope.walletBanks = {{ json_encode(Config::get('dictionary.WALLET_BANKS')) }};

      $scope.reset=reset;
      function reset() {
        $scope.balanceRequestFrm.$submitted = false
          $scope.balanceRequest = {
            distributor:$scope.userType.name,
            parent_id : $scope.userType.id,
            amount : '',
            remarks : ''
          }
      }

      $scope.submit = submit;
      $scope.invalidAmount = invalidAmount;
     
      function submit (balanceRequest) {
        if ($scope.balanceRequestFrm.$invalid || invalidAmount(balanceRequest.amount)) return
        req = Object.assign(balanceRequest, {user_id: $scope.activeUserProfile.id})
       
        $http.post('/api/v1/wallets/balance-requests/from-distributors', req)
        .then(data => {
          sweetAlert('Success', 'Balance request sent to the distributor.', 'success')
          reset();
          location.reload();
          // location.href = "/"
        }, function (err) {
          if (err.data.code && err.data.code == 1) {
            sweetAlert('Error', 'Missing data. Fill all the details please.', 'error')
            return
          }
          if (err.status == 403 || err.status == 401) {
            sweetAlert('Error', 'This request is unauthorized.', 'error')
            return
          }
          sweetAlert('Error', 'Error. Try again.', 'error')
        })
      }

      function invalidAmount (amount) {
        return amount >= 100 && amount <= 2500000 ? false : true
      }
      
      function fail (err) {

        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
