import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:flutex_admin/core/helper/string_format_helper.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/style.dart';

class CustomSnackBar {
  static error({required List<String> errorList, int duration = 2}) {
    String message = '';
    if (errorList.isEmpty) {
      message = LocalStrings.somethingWentWrong.tr;
    } else {
      for (var element in errorList) {
        String tempMessage = element;
        message = message.isEmpty ? tempMessage : "$message\n$tempMessage";
      }
    }
    message = Converter.removeQuotationAndSpecialCharacterFromString(message);
    ScaffoldMessenger.of(Get.context!).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: regularLarge.copyWith(color: ColorResources.colorWhite),
        ),
        backgroundColor: ColorResources.colorRed,
        duration: Duration(seconds: duration),
        behavior: SnackBarBehavior.floating,
        margin: const EdgeInsets.all(Dimensions.space8),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(4)),
      ),
    );
  }

  static success({required List<String> successList, int duration = 2}) {
    String message = '';
    if (successList.isEmpty) {
      message = LocalStrings.somethingWentWrong.tr;
    } else {
      for (var element in successList) {
        String tempMessage = element;
        message = message.isEmpty ? tempMessage : "$message\n$tempMessage";
      }
    }
    message = Converter.removeQuotationAndSpecialCharacterFromString(message);
    ScaffoldMessenger.of(Get.context!).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: regularLarge.copyWith(color: ColorResources.colorWhite),
        ),
        backgroundColor: ColorResources.colorGreen,
        duration: Duration(seconds: duration),
        behavior: SnackBarBehavior.floating,
        margin: const EdgeInsets.all(Dimensions.space8),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(4)),
      ),
    );
  }
}
