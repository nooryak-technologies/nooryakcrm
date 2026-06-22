import 'package:flutex_admin/common/components/app-bar/custom_appbar.dart';
import 'package:flutex_admin/common/components/custom_fab.dart';
import 'package:flutex_admin/common/components/custom_loader/custom_loader.dart';
import 'package:flutex_admin/common/components/no_data.dart';
import 'package:flutex_admin/common/components/overview_card.dart';
import 'package:flutex_admin/common/components/search_field.dart';
import 'package:flutex_admin/common/components/text/text_icon.dart';
import 'package:flutex_admin/core/route/route.dart';
import 'package:flutex_admin/core/service/api_service.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/project/controller/project_controller.dart';
import 'package:flutex_admin/features/project/model/project_model.dart';
import 'package:flutex_admin/features/project/repo/project_repo.dart';
import 'package:flutex_admin/features/project/widget/project_card.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:get/get.dart';

/// Map overview status name → Project.status numeric code
const Map<String, String> _projectStatusCodes = {
  'Not Started': '1',
  'In Progress': '2',
  'On Hold': '3',
  'Finished': '4',
  'Cancelled': '5',
};

class ProjectsScreen extends StatefulWidget {
  const ProjectsScreen({super.key});

  @override
  State<ProjectsScreen> createState() => _ProjectsScreenState();
}

class _ProjectsScreenState extends State<ProjectsScreen> {
  @override
  void initState() {
    Get.put(ApiClient(sharedPreferences: Get.find()));
    Get.put(ProjectRepo(apiClient: Get.find()));
    final controller = Get.put(ProjectController(projectRepo: Get.find()));
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
    return GetBuilder<ProjectController>(
      builder: (controller) {
        // Apply status filter client-side using numeric status code
        final allProjects = controller.projectsModel.data ?? [];
        final filteredProjects = controller.statusFilter == null
            ? allProjects
            : allProjects
                .where((p) => p.status == controller.statusFilter)
                .toList();

        // Build filtered model for card display
        final filteredModel = ProjectsModel(
          status: controller.projectsModel.status,
          message: controller.projectsModel.message,
          overview: controller.projectsModel.overview,
          data: filteredProjects,
        );

        return Scaffold(
          appBar: CustomAppBar(
            title: LocalStrings.projects.tr,
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
                  Get.toNamed(RouteHelper.addProjectScreen);
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
                    controller.statusFilter = null;
                    await controller.initialData(shouldLoad: false);
                  },
                  child: SingleChildScrollView(
                    controller: scrollController,
                    padding:
                        const EdgeInsets.only(bottom: Dimensions.space30),
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: Column(
                      children: [
                        Visibility(
                          visible: controller.isSearch,
                          child: SearchField(
                            title: LocalStrings.projectDetails.tr,
                            searchController: controller.searchController,
                            onTap: () => controller.searchProject(),
                          ),
                        ),
                        // Tappable overview filter cards (like Leads)
                        if (controller.projectsModel.overview != null &&
                            controller.projectsModel.overview!.isNotEmpty)
                          ExpansionTile(
                            title: Row(
                              children: [
                                Container(
                                  width: Dimensions.space3,
                                  height: Dimensions.space15,
                                  color: ColorResources.blueColor,
                                ),
                                const SizedBox(width: Dimensions.space5),
                                Text(
                                  LocalStrings.projectSummery.tr,
                                  style: regularLarge.copyWith(
                                    color: Theme.of(
                                      context,
                                    ).textTheme.bodyMedium!.color,
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
                                  height: 80,
                                  child: ListView.separated(
                                    scrollDirection: Axis.horizontal,
                                    itemCount: controller
                                        .projectsModel.overview!.length,
                                    separatorBuilder: (_, __) =>
                                        const SizedBox(
                                            width: Dimensions.space5),
                                    itemBuilder: (context, index) {
                                      final item = controller
                                          .projectsModel.overview![index];
                                      final statusName = item.status ?? '';
                                      final statusCode =
                                          _projectStatusCodes[statusName];
                                      final isSelected =
                                          controller.statusFilter != null &&
                                              controller.statusFilter ==
                                                  statusCode;
                                      return InkWell(
                                        onTap: () {
                                          if (statusCode != null) {
                                            if (controller.statusFilter ==
                                                statusCode) {
                                              controller.statusFilter = null;
                                            } else {
                                              controller.statusFilter =
                                                  statusCode;
                                            }
                                            controller.update();
                                          }
                                        },
                                        child: OverviewCard(
                                          name: statusName.tr,
                                          number: item.total.toString(),
                                          color: ColorResources.blueColor,
                                          isSelected: isSelected,
                                        ),
                                      );
                                    },
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
                                LocalStrings.projects.tr,
                                style: regularLarge.copyWith(
                                  color: Theme.of(
                                    context,
                                  ).textTheme.bodyMedium!.color,
                                ),
                              ),
                              InkWell(
                                onTap: () {
                                  if (controller.statusFilter != null) {
                                    controller.statusFilter = null;
                                    controller.update();
                                  }
                                },
                                child: TextIcon(
                                  text: LocalStrings.filter.tr,
                                  icon: controller.statusFilter != null
                                      ? Icons.filter_alt
                                      : Icons.sort_outlined,
                                ),
                              ),
                            ],
                          ),
                        ),
                        filteredProjects.isNotEmpty
                            ? ListView.separated(
                                shrinkWrap: true,
                                physics:
                                    const NeverScrollableScrollPhysics(),
                                padding: const EdgeInsets.symmetric(
                                  horizontal: Dimensions.space15,
                                ),
                                itemBuilder: (context, index) {
                                  return ProjectCard(
                                    index: index,
                                    projectModel: filteredModel,
                                  );
                                },
                                separatorBuilder: (context, index) =>
                                    const SizedBox(
                                        height: Dimensions.space10),
                                itemCount: filteredProjects.length,
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
