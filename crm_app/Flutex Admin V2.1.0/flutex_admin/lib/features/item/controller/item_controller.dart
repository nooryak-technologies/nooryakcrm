import 'dart:async';
import 'dart:convert';
import 'package:flutex_admin/common/components/snack_bar/show_custom_snackbar.dart';
import 'package:flutex_admin/common/models/response_model.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/features/item/model/item_details_model.dart';
import 'package:flutex_admin/features/item/model/item_model.dart';
import 'package:flutex_admin/features/item/repo/item_repo.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class ItemController extends GetxController {
  ItemRepo itemRepo;
  ItemController({required this.itemRepo});

  bool isLoading = true;
  bool isSubmitLoading = false;
  ItemsModel itemsModel = ItemsModel();
  ItemDetailsModel itemDetailsModel = ItemDetailsModel();

  // Item Form Controllers
  TextEditingController descriptionController = TextEditingController();
  TextEditingController longDescriptionController = TextEditingController();
  TextEditingController rateController = TextEditingController();
  TextEditingController unitController = TextEditingController();
  
  FocusNode descriptionFocusNode = FocusNode();
  FocusNode longDescriptionFocusNode = FocusNode();
  FocusNode rateFocusNode = FocusNode();
  FocusNode unitFocusNode = FocusNode();

  String? selectedGroupId;
  List<dynamic> groupsList = [];

  Future<void> initialData({bool shouldLoad = true}) async {
    isLoading = shouldLoad ? true : false;
    update();

    await loadItems();
    isLoading = false;
    update();
  }

  Future<void> loadItems() async {
    ResponseModel responseModel = await itemRepo.getAllItems();
    if (responseModel.status) {
      itemsModel = ItemsModel.fromJson(jsonDecode(responseModel.responseJson));
    } else {
      CustomSnackBar.error(errorList: [responseModel.message.tr]);
    }
    isLoading = false;
    update();
  }

  Future<void> loadItemDetails(itemId) async {
    ResponseModel responseModel = await itemRepo.getItemDetails(itemId);
    if (responseModel.status) {
      itemDetailsModel =
          ItemDetailsModel.fromJson(jsonDecode(responseModel.responseJson));
    } else {
      CustomSnackBar.error(errorList: [responseModel.message.tr]);
    }

    isLoading = false;
    update();
  }

  Future<void> loadItemUpdateData(itemId) async {
    isLoading = true;
    update();

    await getItemGroups();
    ResponseModel responseModel = await itemRepo.getItemDetails(itemId);
    if (responseModel.status) {
      itemDetailsModel =
          ItemDetailsModel.fromJson(jsonDecode(responseModel.responseJson));
      descriptionController.text = itemDetailsModel.data?.description ?? '';
      longDescriptionController.text = itemDetailsModel.data?.longDescription ?? '';
      rateController.text = itemDetailsModel.data?.rate ?? '';
      unitController.text = itemDetailsModel.data?.unit ?? '';
      selectedGroupId = itemDetailsModel.data?.groupId;
    } else {
      CustomSnackBar.error(errorList: [responseModel.message.tr]);
    }

    isLoading = false;
    update();
  }

  Future<void> getItemGroups() async {
    ResponseModel responseModel = await itemRepo.getItemGroups();
    if (responseModel.status) {
      final decodedData = jsonDecode(responseModel.responseJson);
      if (decodedData['data'] != null) {
        groupsList = decodedData['data'];
      }
    }
    update();
  }

  Future<void> submitItem({String? itemId, bool isUpdate = false}) async {
    String description = descriptionController.text.trim();
    String rate = rateController.text.trim();

    if (description.isEmpty) {
      CustomSnackBar.error(errorList: ['Description is required']);
      return;
    }
    if (rate.isEmpty) {
      CustomSnackBar.error(errorList: ['Rate is required']);
      return;
    }

    isSubmitLoading = true;
    update();

    Map<String, dynamic> params = {
      'description': description,
      'rate': rate,
      'long_description': longDescriptionController.text.trim(),
      'unit': unitController.text.trim(),
    };

    if (selectedGroupId != null) {
      params['group_id'] = selectedGroupId;
    }

    ResponseModel responseModel = await itemRepo.createItem(
      params,
      itemId: itemId,
      isUpdate: isUpdate,
    );

    final decoded = jsonDecode(responseModel.responseJson);
    if (responseModel.status && decoded['status'] != false) {
      clearItemData();
      Get.back();
      if (isUpdate) {
        await loadItemDetails(itemId);
      }
      await initialData();
      CustomSnackBar.success(
        successList: [
          isUpdate ? 'Item updated successfully' : 'Item added successfully'
        ],
      );
    } else {
      String msg = decoded['message'] ?? 'Operation failed';
      CustomSnackBar.error(errorList: [msg.tr]);
    }

    isSubmitLoading = false;
    update();
  }

  Future<void> deleteItem(itemId) async {
    isLoading = true;
    update();

    ResponseModel response = await itemRepo.deleteItem(itemId);
    final decoded = jsonDecode(response.responseJson);
    if (response.status && decoded['status'] != false) {
      Get.back(); // close confirmation dialog
      Get.back(); // close details screen
      await initialData();
      CustomSnackBar.success(successList: ['Item deleted successfully']);
    } else {
      String msg = decoded['message'] ?? 'Delete failed';
      CustomSnackBar.error(errorList: [msg.tr]);
    }

    isLoading = false;
    update();
  }

  void clearItemData() {
    descriptionController.clear();
    longDescriptionController.clear();
    rateController.clear();
    unitController.clear();
    selectedGroupId = null;
  }

  // Search Items
  TextEditingController searchController = TextEditingController();
  String keysearch = "";

  Future<void> searchItem() async {
    keysearch = searchController.text;
    ResponseModel responseModel = await itemRepo.searchItem(keysearch);
    if (responseModel.status) {
      itemsModel = ItemsModel.fromJson(jsonDecode(responseModel.responseJson));
    } else {
      CustomSnackBar.error(errorList: [responseModel.message.tr]);
    }

    isLoading = false;
    update();
  }

  bool isSearch = false;
  void changeSearchIcon() {
    isSearch = !isSearch;
    update();

    if (!isSearch) {
      searchController.clear();
      initialData();
    }
  }
}
