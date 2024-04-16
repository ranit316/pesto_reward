@include('admin.mailheader')

               <tr>
                  <td height="30"></td>
              </tr>
              <tr>
               
                @if($maildata['message'] == "Success") 
                  <td style="padding-bottom: 15px; border-top: 1px solid #e4e4e4;">
                    <p style="font-family: Helvetica, sans-serif; font-size: 17px; font-weight: normal; margin: 0; margin-bottom: 16px;">Dear {{$maildata['name']}},</p>
                    <p style="font-family: Helvetica, sans-serif; font-size: 17px; font-weight: normal; margin: 0; margin-bottom: 16px; margin-top: 10px;">Congratulations! Your profile verification for Presto Plast India Rewards App was successful. Welcome aboard!Ref No: {{$maildata['referance_code']}}
                        </p>
                  </td>
                 
                  @else 
                  <td style="padding-bottom: 15px; border-top: 1px solid #e4e4e4;">
                    <p style="font-family: Helvetica, sans-serif; font-size: 17px; font-weight: normal; margin: 0; margin-bottom: 16px;">Dear {{$maildata['name']}},</p>
                    <p style="font-family: Helvetica, sans-serif; font-size: 17px; font-weight: normal; margin: 0; margin-bottom: 16px; margin-top: 10px;"> We're sorry, but your profile verification for Presto Plast India Rewards App failed. Please try again or contact support for assistance.</p>
                    <p style="font-family: Helvetica, sans-serif; font-size: 17px; font-weight: normal; margin: 0; margin-bottom: 16px;">Need help? Contact support at 1111111111 or visit https://www.prestorewardsapp.com/.</p>
                  </td>
                  @endif 
                 
              </tr>
              <tr>
                  <td height="20"></td>
              </tr>
              <tr>
                  
            <td>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                <tbody>
                  <tr>
                    <td height="10" colspan="4"></td>
                  </tr>
                  {{-- <tr>
                    <td style="font-size: 14px; font-family: 'Open Sans', sans-serif; color: #1a1919; line-height: 23px; vertical-align: top; padding:10px; border-bottom: 1px solid #e4e4e4;" class="article">
                      <strong>Username:</strong>
                    </td>   
                      <td style="font-size: 14px; font-family: 'Open Sans', sans-serif; color: #1a1919;  line-height: 23px;  vertical-align: top; padding:10px; border-bottom: 1px solid #e4e4e4;" align="right">{{$maildata['name']}}</td>
                  </tr> --}}
                </tbody>
              </table>
            </td>
          </tr>
              
               <tr>
                  <td height="20"></td>
              </tr>
              
              <tr>
                <td>
                    <p>Thank You!</p>
                    <p>Best Regards,</p>
                    <p> {!!optional(DB::table('setting_apps')->first())->title!!} </p>
                </td>
              </tr>
              
              
              {{-- <tr>
                  <td style="text-align: center;">
                      <a href="#" style="text-align: center;
                              display: inline-block;
                              background: #1877f2;
                              padding: 10px 16px;
                              color: #fff;
                              border-radius: 5px;
                              text-decoration: none;
                              margin-top: 25px;">Get More Details</a> 
                  </td>
              </tr> --}}
              
              <tr>
                  <td height="40"></td>
              </tr>
              
              @include('admin.mailfooter')