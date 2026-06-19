import 'package:flutex_admin/common/components/app-bar/custom_appbar.dart';
import 'package:flutex_admin/common/components/buttons/rounded_button.dart';
import 'package:flutex_admin/common/components/buttons/rounded_loading_button.dart';
import 'package:flutex_admin/common/components/text-form-field/custom_text_field.dart';
import 'package:flutex_admin/common/components/custom_loader/custom_loader.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/item/controller/item_controller.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class AddItemScreen extends StatefulWidget {
  const AddItemScreen({super.key});

  @override
  State<AddItemScreen> createState() => _AddItemScreenState();
}

class _AddItemScreenState extends State<AddItemScreen> {
  final _formKey = GlobalKey<FormState>();
  bool isEdit = false;
  String itemId = '';

  @override
  void initState() {
    super.initState();
    final args = Get.arguments;
    if (args != null && args is Map && args['isEdit'] == true) {
      isEdit = true;
      itemId = args['id']?.toString() ?? '';
      WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
        Get.find<ItemController>().loadItemUpdateData(itemId);
      });
    } else {
      isEdit = false;
      itemId = '';
      WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
        final controller = Get.find<ItemController>();
        controller.clearItemData();
        controller.getItemGroups();
        controller.isLoading = false;
        controller.update();
      });
    }
  }

  @override
  void dispose() {
    Get.find<ItemController>().clearItemData();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return GetBuilder<ItemController>(
      builder: (controller) {
        return Scaffold(
          appBar: CustomAppBar(
            title: isEdit ? 'Edit Item' : 'Add New Item',
          ),
          body: controller.isLoading
              ? const CustomLoader()
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(Dimensions.space15),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        CustomTextField(
                          labelText: 'Description *',
                          controller: controller.descriptionController,
                          focusNode: controller.descriptionFocusNode,
                          textInputType: TextInputType.text,
                          nextFocus: controller.longDescriptionFocusNode,
                          onChanged: (value) {},
                          validator: (value) {
                            if (value == null || value.trim().isEmpty) {
                              return 'Description is required';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: Dimensions.space15),
                        CustomTextField(
                          labelText: 'Long Description',
                          controller: controller.longDescriptionController,
                          focusNode: controller.longDescriptionFocusNode,
                          textInputType: TextInputType.multiline,
                          maxLines: 3,
                          onChanged: (value) {},
                          nextFocus: controller.rateFocusNode,
                        ),
                        const SizedBox(height: Dimensions.space15),
                        CustomTextField(
                          labelText: 'Rate *',
                          controller: controller.rateController,
                          focusNode: controller.rateFocusNode,
                          textInputType: const TextInputType.numberWithOptions(decimal: true),
                          nextFocus: controller.unitFocusNode,
                          onChanged: (value) {},
                          validator: (value) {
                            if (value == null || value.trim().isEmpty) {
                              return 'Rate is required';
                            }
                            if (double.tryParse(value) == null) {
                              return 'Please enter a valid rate';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: Dimensions.space15),
                        CustomTextField(
                          labelText: 'Unit',
                          controller: controller.unitController,
                          focusNode: controller.unitFocusNode,
                          textInputType: TextInputType.text,
                          onChanged: (value) {},
                          inputAction: TextInputAction.done,
                        ),
                        const SizedBox(height: Dimensions.space15),
                        
                        // Item Group Dropdown
                        Text(
                          'Item Group',
                          style: regularDefault.copyWith(
                            color: Theme.of(context).textTheme.bodyLarge!.color,
                          ),
                        ),
                        const SizedBox(height: Dimensions.space5),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: Dimensions.space10),
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(Dimensions.defaultRadius),
                            border: Border.all(
                              color: ColorResources.getTextFieldDisableBorder(),
                              width: 1.0,
                            ),
                          ),
                          child: DropdownButtonHideUnderline(
                            child: DropdownButton<String>(
                              isExpanded: true,
                              value: controller.selectedGroupId,
                              hint: Text(
                                'Select Item Group',
                                style: regularDefault.copyWith(color: ColorResources.blueGreyColor),
                              ),
                              dropdownColor: Theme.of(context).cardColor,
                              style: regularDefault.copyWith(
                                color: Theme.of(context).textTheme.bodyLarge!.color,
                              ),
                              items: controller.groupsList.map((group) {
                                return DropdownMenuItem<String>(
                                  value: group['id']?.toString(),
                                  child: Text(group['name'] ?? ''),
                                );
                              }).toList(),
                              onChanged: (value) {
                                controller.selectedGroupId = value;
                                controller.update();
                              },
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
          bottomNavigationBar: Padding(
            padding: const EdgeInsets.all(Dimensions.space10),
            child: controller.isSubmitLoading
                ? const RoundedLoadingBtn()
                : RoundedButton(
                    text: LocalStrings.submit.tr,
                    press: () {
                      if (_formKey.currentState?.validate() ?? false) {
                        if (isEdit) {
                          controller.submitItem(itemId: itemId, isUpdate: true);
                        } else {
                          controller.submitItem();
                        }
                      }
                    },
                  ),
          ),
        );
      },
    );
  }
}
