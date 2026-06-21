import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutex_admin/core/utils/url_container.dart';

import 'package:flutex_admin/features/auth/model/login_model.dart';
import 'package:flutex_admin/features/auth/repo/auth_repo.dart';
import 'package:flutter/cupertino.dart';
import 'package:get/get.dart';
import 'package:flutex_admin/core/helper/shared_preference_helper.dart';
import 'package:flutex_admin/core/route/route.dart';
import 'package:flutex_admin/common/models/response_model.dart';
import 'package:flutex_admin/common/components/snack_bar/show_custom_snackbar.dart';

class LoginController extends GetxController {
  AuthRepo loginRepo;

  final FocusNode emailFocusNode = FocusNode();
  final FocusNode passwordFocusNode = FocusNode();

  TextEditingController emailController =
      TextEditingController(text: 'admin@demo.com');
  TextEditingController passwordController =
      TextEditingController(text: '123456');

  String? email;
  String? password;
  bool remember = false;

  LoginController({required this.loginRepo});

  Future<void> checkAndGotoNextStep(LoginModel responseModel) async {
    if (remember) {
      await loginRepo.apiClient.sharedPreferences
          .setBool(SharedPreferenceHelper.rememberMeKey, true);
    } else {
      await loginRepo.apiClient.sharedPreferences
          .setBool(SharedPreferenceHelper.rememberMeKey, false);
    }

    await loginRepo.apiClient.sharedPreferences.setString(
        SharedPreferenceHelper.userIdKey,
        responseModel.data?.staffId.toString() ?? '-1');
    await loginRepo.apiClient.sharedPreferences.setString(
        SharedPreferenceHelper.accessTokenKey,
        responseModel.data?.accessToken.toString() ?? '');

    await loginRepo.updateToken();
    Get.offAllNamed(RouteHelper.dashboardScreen);

    if (remember) {
      changeRememberMe();
    }
  }

  bool isSubmitLoading = false;

  void loginUser() async {
    isSubmitLoading = true;
    update();

    String emailVal = emailController.text.trim();
    String passwordVal = passwordController.text;

    // Default main domain
    String defaultDomain = 'https://nooryakcrm.com';

    try {
      // 1. Perform lookup to find the tenant's domain
      var lookupResponse = await http.post(
        Uri.parse('$defaultDomain/flutex_admin_api/auth/lookup'),
        body: {'email': emailVal},
      );

      if (lookupResponse.statusCode == 200) {
        var data = jsonDecode(lookupResponse.body);
        if (data['status'] == true && data['domain'] != null) {
          String newDomain = data['domain'].toString();
          // Remove trailing slash if present
          if (newDomain.endsWith('/')) {
            newDomain = newDomain.substring(0, newDomain.length - 1);
          }
          UrlContainer.domainUrl = newDomain;
          await loginRepo.apiClient.sharedPreferences.setString(
            SharedPreferenceHelper.domainUrlKey,
            newDomain,
          );
        }
      } else {
        // If lookup fails (e.g. 404), reset to default main domain
        UrlContainer.domainUrl = defaultDomain;
        await loginRepo.apiClient.sharedPreferences.setString(
          SharedPreferenceHelper.domainUrlKey,
          defaultDomain,
        );
      }
    } catch (e) {
      debugPrint('Lookup error: $e');
    }

    ResponseModel responseModel = await loginRepo.loginUser(
        emailVal, passwordVal);

    if (responseModel.status) {
      LoginModel loginModel =
          LoginModel.fromJson(jsonDecode(responseModel.responseJson));
      checkAndGotoNextStep(loginModel);
    } else {
      CustomSnackBar.error(errorList: [responseModel.message.tr]);
    }
    isSubmitLoading = false;
    update();
  }

  changeRememberMe() {
    remember = !remember;
    update();
  }

  void clearTextField() {
    passwordController.text = '';
    emailController.text = '';
    if (remember) {
      remember = false;
    }
    update();
  }
}
