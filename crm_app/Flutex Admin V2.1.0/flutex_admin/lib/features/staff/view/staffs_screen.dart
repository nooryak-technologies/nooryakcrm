import 'package:flutex_admin/common/components/app-bar/custom_appbar.dart';
import 'package:flutex_admin/common/components/custom_fab.dart';
import 'package:flutex_admin/common/components/custom_loader/custom_loader.dart';
import 'package:flutex_admin/common/components/no_data.dart';
import 'package:flutex_admin/common/components/search_field.dart';
import 'package:flutex_admin/core/route/route.dart';
import 'package:flutex_admin/core/service/api_service.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/features/staff/controller/staff_controller.dart';
import 'package:flutex_admin/features/staff/repo/staff_repo.dart';
import 'package:flutex_admin/features/staff/widget/staffs_card.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:get/get.dart';
import 'package:flutex_admin/common/components/overview_card.dart';
import 'package:flutex_admin/common/components/text/text_icon.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/staff/model/staff_model.dart';

class StaffsScreen extends StatefulWidget {
  const StaffsScreen({super.key});

  @override
  State<StaffsScreen> createState() => _StaffsScreenState();
}

class _StaffsScreenState extends State<StaffsScreen> {
  @override
  void initState() {
    Get.put(ApiClient(sharedPreferences: Get.find()));
    Get.put(StaffRepo(apiClient: Get.find()));
    final controller = Get.put(StaffController(staffRepo: Get.find()));
    controller.isLoading = true;
    super.initState();
    handleScroll();

    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      controller.initialData();
    });
  }

  bool showFab = true;
  ScrollController scrollController = ScrollController();

  @override
  void dispose() {
    scrollController.removeListener(() {});
    super.dispose();
  }

  void handleScroll() async {
    scrollController.addListener(() {
      if (scrollController.position.userScrollDirection ==
          ScrollDirection.reverse) {
        if (showFab) setState(() => showFab = false);
      }
      if (scrollController.position.userScrollDirection ==
          ScrollDirection.forward) {
        if (!showFab) setState(() => showFab = true);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return GetBuilder<StaffController>(
      builder: (controller) {
        final allStaff = controller.staffsModel.data ?? [];
        final filteredStaff = controller.activeFilter == null
            ? allStaff
            : allStaff
                .where((s) => s.active == controller.activeFilter)
                .toList();

        return Scaffold(
          appBar: CustomAppBar(
            title: LocalStrings.staffs.tr,
            isShowActionBtn: true,
            actionWidget: IconButton(
              onPressed: () => controller.changeSearchIcon(),
              icon: Icon(controller.isSearch ? Icons.clear : Icons.search),
            ),
          ),
          floatingActionButton: AnimatedSlide(
            offset: showFab ? Offset.zero : const Offset(0, 2),
            duration: const Duration(milliseconds: 300),
            child: AnimatedOpacity(
              opacity: showFab ? 1 : 0,
              duration: const Duration(milliseconds: 300),
              child: CustomFAB(
                isShowIcon: true,
                isShowText: false,
                press: () {
                  Get.toNamed(RouteHelper.addStaffScreen);
                },
              ),
            ),
          ),
          body: controller.isLoading
              ? const CustomLoader()
              : RefreshIndicator(
                  color: Theme.of(context).primaryColor,
                  backgroundColor: Theme.of(context).cardColor,
                  onRefresh: () async {
                    controller.activeFilter = null;
                    await controller.initialData(shouldLoad: false);
                  },
                  child: SingleChildScrollView(
                    controller: scrollController,
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Visibility(
                          visible: controller.isSearch,
                          child: SearchField(
                            title: LocalStrings.staffDetails.tr,
                            searchController: controller.searchController,
                            onTap: () => controller.searchStaff(),
                          ),
                        ),
                        if (controller.staffsModel.overview != null)
                          ExpansionTile(
                            title: Row(
                              children: [
                                Container(
                                  width: Dimensions.space3,
                                  height: Dimensions.space15,
                                  color: Colors.blue,
                                ),
                                const SizedBox(width: Dimensions.space5),
                                Text(
                                  LocalStrings.staffSummery.tr,
                                  style: regularLarge.copyWith(
                                    color: Theme.of(context)
                                        .textTheme
                                        .bodyMedium!
                                        .color,
                                  ),
                                ),
                              ],
                            ),
                            shape: const Border(),
                            initiallyExpanded: true,
                            children: [
                              Padding(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: Dimensions.space15,
                                ),
                                child: SizedBox(
                                  height: 85,
                                  child: ListView(
                                    scrollDirection: Axis.horizontal,
                                    children: [
                                      InkWell(
                                        onTap: () {
                                          controller.activeFilter = null;
                                          controller.update();
                                        },
                                        child: Padding(
                                          padding: const EdgeInsets.only(
                                              right: Dimensions.space5),
                                          child: OverviewCard(
                                            name: LocalStrings.totalStaff.tr,
                                            number: controller.staffsModel
                                                    .overview?.staffTotal ??
                                                '0',
                                            color: ColorResources.blueColor,
                                            isSelected:
                                                controller.activeFilter == null,
                                          ),
                                        ),
                                      ),
                                      InkWell(
                                        onTap: () {
                                          if (controller.activeFilter == '1') {
                                            controller.activeFilter = null;
                                          } else {
                                            controller.activeFilter = '1';
                                          }
                                          controller.update();
                                        },
                                        child: Padding(
                                          padding: const EdgeInsets.only(
                                              right: Dimensions.space5),
                                          child: OverviewCard(
                                            name: LocalStrings.activeStaff.tr,
                                            number: controller.staffsModel
                                                    .overview
                                                    ?.staffActive ??
                                                '0',
                                            color: ColorResources.greenColor,
                                            isSelected:
                                                controller.activeFilter == '1',
                                          ),
                                        ),
                                      ),
                                      InkWell(
                                        onTap: () {
                                          if (controller.activeFilter == '0') {
                                            controller.activeFilter = null;
                                          } else {
                                            controller.activeFilter = '0';
                                          }
                                          controller.update();
                                        },
                                        child: Padding(
                                          padding: const EdgeInsets.only(
                                              right: Dimensions.space5),
                                          child: OverviewCard(
                                            name: LocalStrings.inactiveStaff.tr,
                                            number: controller.staffsModel
                                                    .overview
                                                    ?.staffInactive ??
                                                '0',
                                            color: ColorResources.redColor,
                                            isSelected:
                                                controller.activeFilter == '0',
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        Padding(
                          padding: const EdgeInsets.all(Dimensions.space15),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                LocalStrings.staffs.tr,
                                style: regularLarge.copyWith(
                                  color: Theme.of(context)
                                      .textTheme
                                      .bodyMedium!
                                      .color,
                                ),
                              ),
                              InkWell(
                                onTap: () {
                                  if (controller.activeFilter != null) {
                                    controller.activeFilter = null;
                                    controller.update();
                                  }
                                },
                                child: TextIcon(
                                  text: LocalStrings.filter.tr,
                                  icon: controller.activeFilter != null
                                      ? Icons.filter_alt
                                      : Icons.sort_outlined,
                                ),
                              ),
                            ],
                          ),
                        ),
                        filteredStaff.isNotEmpty
                            ? Padding(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: Dimensions.space15,
                                ),
                                child: ListView.builder(
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
                                  itemBuilder: (context, index) {
                                    return StaffsCard(
                                      staffModel: filteredStaff[index],
                                    );
                                  },
                                  itemCount: filteredStaff.length,
                                ),
                              )
                            : const NoDataWidget(),
                      ],
                    ),
                  ),
                ),
        );
      },
    );
  }
}
