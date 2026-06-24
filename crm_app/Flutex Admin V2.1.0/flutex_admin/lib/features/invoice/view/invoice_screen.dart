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
import 'package:flutex_admin/features/invoice/controller/invoice_controller.dart';
import 'package:flutex_admin/features/invoice/repo/invoice_repo.dart';
import 'package:flutex_admin/features/invoice/widget/invoice_card.dart';
import 'package:flutex_admin/features/invoice/model/invoice_model.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:get/get.dart';

/// Map invoice status display name → numeric code used in Invoice.status
const Map<String, String> _invoiceStatusCodes = {
  'Unpaid': '1',
  'Paid': '2',
  'Partial': '3',
  'Partially paid': '3',
  'Partially Paid': '3',
  'Overdue': '4',
  'Cancelled': '5',
  'Draft': '6',
};

class InvoicesScreen extends StatefulWidget {
  const InvoicesScreen({super.key});

  @override
  State<InvoicesScreen> createState() => _InvoicesScreenState();
}

class _InvoicesScreenState extends State<InvoicesScreen> {
  @override
  void initState() {
    Get.put(ApiClient(sharedPreferences: Get.find()));
    Get.put(InvoiceRepo(apiClient: Get.find()));
    final controller = Get.put(InvoiceController(invoiceRepo: Get.find()));
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
    return GetBuilder<InvoiceController>(builder: (controller) {
      // Apply status filter client-side
      final allInvoices = controller.invoicesModel.data ?? [];
      final filteredInvoices = controller.statusFilter == null
          ? allInvoices
          : allInvoices
              .where((inv) => inv.status == controller.statusFilter)
              .toList();

      // Build filtered model for card rendering
      final filteredModel = InvoicesModel(
        status: controller.invoicesModel.status,
        message: controller.invoicesModel.message,
        overview: controller.invoicesModel.overview,
        data: filteredInvoices,
        advancePaidTotal: controller.invoicesModel.advancePaidTotal,
        advancePaidCount: controller.invoicesModel.advancePaidCount,
        pendingPaymentTotal: controller.invoicesModel.pendingPaymentTotal,
        pendingPaymentCount: controller.invoicesModel.pendingPaymentCount,
        currencySymbol: controller.invoicesModel.currencySymbol,
      );

      return Scaffold(
        appBar: CustomAppBar(
          title: LocalStrings.invoices.tr,
          isShowActionBtn: true,
          actionWidget: IconButton(
              onPressed: () => controller.changeSearchIcon(),
              icon: Icon(controller.isSearch ? Icons.clear : Icons.search)),
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
                  Get.toNamed(RouteHelper.addInvoiceScreen);
                }),
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
                  physics: const AlwaysScrollableScrollPhysics(),
                  child: Column(
                    children: [
                      Visibility(
                        visible: controller.isSearch,
                        child: SearchField(
                          title: LocalStrings.invoiceDetails.tr,
                          searchController: controller.searchController,
                          onTap: () => controller.searchInvoice(),
                        ),
                      ),
                      // Overview status filter cards (like Leads)
                      if (controller.invoicesModel.overview != null &&
                          controller.invoicesModel.overview!.isNotEmpty)
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
                                LocalStrings.invoiceSummery.tr,
                                style: regularLarge.copyWith(
                                    color: Theme.of(context)
                                        .textTheme
                                        .bodyMedium!
                                        .color),
                              ),
                            ],
                          ),
                          shape: const Border(),
                          initiallyExpanded: true,
                          children: [
                            Padding(
                              padding: const EdgeInsets.symmetric(
                                  horizontal: Dimensions.space15),
                              child: SizedBox(
                                height: 85,
                                child: ListView.separated(
                                  scrollDirection: Axis.horizontal,
                                  itemCount: controller
                                      .invoicesModel.overview!.length,
                                  separatorBuilder: (_, __) => const SizedBox(
                                      width: Dimensions.space5),
                                  itemBuilder: (context, index) {
                                    final item = controller
                                        .invoicesModel.overview![index];
                                    final statusName = item.status ?? '';
                                    final statusCode =
                                        _invoiceStatusCodes[statusName];
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
                                        number: item.total ?? '0',
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
                              LocalStrings.invoices.tr,
                              style: regularLarge.copyWith(
                                  color: Theme.of(context)
                                      .textTheme
                                      .bodyMedium!
                                      .color),
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
                                      : Icons.sort_outlined),
                            ),
                          ],
                        ),
                      ),
                      filteredInvoices.isNotEmpty
                          ? ListView.separated(
                              padding: const EdgeInsets.symmetric(
                                  horizontal: Dimensions.space15),
                              shrinkWrap: true,
                              physics: const NeverScrollableScrollPhysics(),
                              itemBuilder: (context, index) {
                                return InvoiceCard(
                                  index: index,
                                  invoiceModel: filteredModel,
                                );
                              },
                              separatorBuilder: (context, index) =>
                                  const SizedBox(height: Dimensions.space10),
                              itemCount: filteredInvoices.length)
                          : const NoDataWidget(),
                    ],
                  ),
                ),
              ),
      );
    });
  }
}
