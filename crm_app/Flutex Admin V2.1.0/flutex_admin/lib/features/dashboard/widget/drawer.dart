import 'package:flutex_admin/common/components/circle_image_button.dart';
import 'package:flutex_admin/common/components/dialog/warning_dialog.dart';
import 'package:flutex_admin/core/route/route.dart';
import 'package:flutex_admin/core/utils/images.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/features/dashboard/controller/dashboard_controller.dart';
import 'package:flutex_admin/features/dashboard/model/dashboard_model.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/core/utils/url_container.dart';
import 'package:url_launcher/url_launcher.dart';

class HomeDrawer extends StatelessWidget {
  const HomeDrawer({super.key, required this.homeModel});
  final DashboardModel homeModel;

  void _openWebUrl(String path) async {
    final String url = '${UrlContainer.domainUrl}/$path';
    final Uri uri = Uri.parse(url);
    try {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    } catch (e) {
      // Handle error
    }
  }

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: Column(
        children: [
          UserAccountsDrawerHeader(
            accountName: Text(
              '${homeModel.staff?.firstName ?? ''} ${homeModel.staff?.lastName ?? ''}',
              overflow: TextOverflow.ellipsis,
              maxLines: 2,
              style: mediumLarge.copyWith(color: Colors.white),
            ),
            accountEmail: Text(
              homeModel.staff?.email ?? '',
              style: lightDefault.copyWith(color: Colors.white),
            ),
            onDetailsPressed: () {
              Get.back();
              Get.toNamed(RouteHelper.profileScreen);
            },
            currentAccountPicture: CircleAvatar(
              child: CircleImageWidget(
                imagePath: homeModel.staff?.profileImage ?? '',
                isAsset: false,
                isProfile: true,
                width: 80,
                height: 80,
              ),
            ),
            decoration: BoxDecoration(
              image: DecorationImage(
                colorFilter: ColorFilter.mode(
                  ColorResources.primaryColor.withValues(alpha: 0.6),
                  BlendMode.multiply,
                ),
                image: AssetImage(MyImages.login),
                fit: BoxFit.fill,
              ),
            ),
          ),
          Expanded(
            child: SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Column(
                children: [
                  homeModel.menuItems?.customers ?? false
                      ? buildListTile(
                          leadingIcon: Icons.person_outline,
                          title: LocalStrings.customers.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.customerScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  ExpansionTile(
                    title: Text(
                      LocalStrings.sales.tr,
                      style: regularDefault.copyWith(
                        color: Theme.of(context).textTheme.bodyLarge!.color,
                      ),
                    ),
                    leading: Icon(
                      Icons.electric_bolt_rounded,
                      color: Theme.of(context).textTheme.bodyLarge!.color,
                    ),
                    iconColor: Theme.of(
                      Get.context!,
                    ).textTheme.bodyLarge!.color,
                    collapsedIconColor: Theme.of(
                      Get.context!,
                    ).textTheme.bodyLarge!.color,
                    children: [
                      homeModel.menuItems?.proposals ?? false
                          ? buildListTile(
                              leadingIcon: Icons.document_scanner_outlined,
                              title: LocalStrings.proposals.tr,
                              onTap: () {
                                Navigator.pop(context);
                                Get.toNamed(RouteHelper.proposalScreen);
                              },
                            )
                          : const SizedBox.shrink(),
                      homeModel.menuItems?.estimates ?? false
                          ? buildListTile(
                              leadingIcon: Icons.add_chart_outlined,
                              title: LocalStrings.estimates.tr,
                              onTap: () {
                                Navigator.pop(context);
                                Get.toNamed(RouteHelper.estimateScreen);
                              },
                            )
                          : const SizedBox.shrink(),
                      homeModel.menuItems?.invoices ?? false
                          ? buildListTile(
                              leadingIcon: Icons.assignment_outlined,
                              title: LocalStrings.invoices.tr,
                              onTap: () {
                                Navigator.pop(context);
                                Get.toNamed(RouteHelper.invoiceScreen);
                              },
                            )
                          : const SizedBox.shrink(),
                      homeModel.menuItems?.payments ?? false
                          ? buildListTile(
                              leadingIcon:
                                  Icons.account_balance_wallet_outlined,
                              title: LocalStrings.payments.tr,
                              onTap: () {
                                Navigator.pop(context);
                                Get.toNamed(RouteHelper.paymentScreen);
                              },
                            )
                          : const SizedBox.shrink(),
                      homeModel.menuItems?.creditNotes ?? false
                          ? buildListTile(
                              leadingIcon: Icons.receipt_long_outlined,
                              title: LocalStrings.creditNotes.tr,
                              onTap: () {
                                Navigator.pop(context);
                                _openWebUrl('admin/credit_notes');
                              },
                            )
                          : const SizedBox.shrink(),
                      homeModel.menuItems?.items ?? false
                          ? buildListTile(
                              leadingIcon: Icons.add_box_outlined,
                              title: LocalStrings.items.tr,
                              onTap: () {
                                Navigator.pop(context);
                                Get.toNamed(RouteHelper.itemScreen);
                              },
                            )
                          : const SizedBox.shrink(),
                    ],
                  ),
                  homeModel.menuItems?.projects ?? false
                      ? buildListTile(
                          leadingIcon: Icons.folder_open_outlined,
                          title: LocalStrings.projects.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.projectScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.tasks ?? false
                      ? buildListTile(
                          leadingIcon: Icons.task_alt_rounded,
                          title: LocalStrings.tasks.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.taskScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.contracts ?? false
                      ? buildListTile(
                          leadingIcon: Icons.article_outlined,
                          title: LocalStrings.contracts.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.contractScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.tickets ?? false
                      ? buildListTile(
                          leadingIcon: Icons.confirmation_number_outlined,
                          title: LocalStrings.support.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.ticketScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.leads ?? false
                      ? buildListTile(
                          leadingIcon: Icons.markunread_mailbox_outlined,
                          title: LocalStrings.leads.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.leadScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.expenses ?? false
                      ? buildListTile(
                          leadingIcon: Icons.monetization_on_outlined,
                          title: LocalStrings.expenses.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.expenseScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.staff ?? false
                      ? buildListTile(
                          leadingIcon: Icons.person_4_outlined,
                          title: LocalStrings.staffs.tr,
                          onTap: () {
                            Navigator.pop(context);
                            Get.toNamed(RouteHelper.staffScreen);
                          },
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.utilities ?? false
                      ? ExpansionTile(
                          title: Text(
                            LocalStrings.utilities.tr,
                            style: regularDefault.copyWith(
                              color: Theme.of(context).textTheme.bodyLarge!.color,
                            ),
                          ),
                          leading: Icon(
                            Icons.circle_outlined,
                            color: Theme.of(context).textTheme.bodyLarge!.color,
                          ),
                          iconColor: Theme.of(
                            Get.context!,
                          ).textTheme.bodyLarge!.color,
                          collapsedIconColor: Theme.of(
                            Get.context!,
                          ).textTheme.bodyLarge!.color,
                          children: [
                            homeModel.menuItems?.media ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.perm_media_outlined,
                                    title: LocalStrings.media.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/utilities/media');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.bulkPdfExporter ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.picture_as_pdf_outlined,
                                    title: LocalStrings.bulkPdfExporter.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/utilities/bulk_pdf_exporter');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.calendar ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.calendar_month_outlined,
                                    title: LocalStrings.calendar.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/utilities/calendar');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.announcements ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.campaign_outlined,
                                    title: LocalStrings.announcements.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/announcements');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.activityLog ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.history_outlined,
                                    title: LocalStrings.activityLog.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/utilities/activity_log');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.ticketPipeLog ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.settings_phone_outlined,
                                    title: LocalStrings.ticketPipeLog.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/utilities/pipe_log');
                                    },
                                  )
                                : const SizedBox.shrink(),
                          ],
                        )
                      : const SizedBox.shrink(),
                  homeModel.menuItems?.reports ?? false
                      ? ExpansionTile(
                          title: Text(
                            LocalStrings.reports.tr,
                            style: regularDefault.copyWith(
                              color: Theme.of(context).textTheme.bodyLarge!.color,
                            ),
                          ),
                          leading: Icon(
                            Icons.show_chart_rounded,
                            color: Theme.of(context).textTheme.bodyLarge!.color,
                          ),
                          iconColor: Theme.of(
                            Get.context!,
                          ).textTheme.bodyLarge!.color,
                          collapsedIconColor: Theme.of(
                            Get.context!,
                          ).textTheme.bodyLarge!.color,
                          children: [
                            homeModel.menuItems?.salesReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.trending_up_outlined,
                                    title: LocalStrings.salesReports.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/reports/sales');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.expensesReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.money_off_outlined,
                                    title: LocalStrings.expensesReports.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/reports/expenses');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.expensesVsIncomeReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.compare_arrows_outlined,
                                    title: LocalStrings.expensesVsIncome.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/reports/expenses_vs_income');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.leadsReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.filter_alt_outlined,
                                    title: LocalStrings.leadsReports.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/reports/leads');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.kbReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.menu_book_outlined,
                                    title: LocalStrings.kbArticlesReports.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/reports/knowledge_base_articles');
                                    },
                                  )
                                : const SizedBox.shrink(),
                            homeModel.menuItems?.timesheetsReports ?? false
                                ? buildListTile(
                                    leadingIcon: Icons.timer_outlined,
                                    title: LocalStrings.timesheets.tr,
                                    onTap: () {
                                      Navigator.pop(context);
                                      _openWebUrl('admin/staff/timesheets?view=all');
                                    },
                                  )
                                : const SizedBox.shrink(),
                          ],
                        )
                      : const SizedBox.shrink(),
                  buildListTile(
                    leadingIcon: Icons.settings_outlined,
                    title: LocalStrings.settings.tr,
                    onTap: () {
                      Navigator.pop(context);
                      Get.toNamed(RouteHelper.settingsScreen);
                    },
                  ),
                ],
              ),
            ),
          ),
          ListTile(
            leading: const Icon(
              Icons.logout,
              size: Dimensions.space20,
              color: Colors.red,
            ),
            title: Text(
              LocalStrings.logout.tr,
              style: regularDefault.copyWith(
                color: Theme.of(context).textTheme.bodyLarge!.color,
              ),
            ),
            onTap: () {
              const WarningAlertDialog().warningAlertDialog(
                context,
                () {
                  Get.back();
                  Get.find<DashboardController>().logout();
                },
                title: LocalStrings.logout.tr,
                subTitle: LocalStrings.logoutSureWarningMSg.tr,
              );
            },
          ),
        ],
      ),
    );
  }

  Widget buildListTile({
    required IconData leadingIcon,
    required String title,
    required VoidCallback onTap,
  }) {
    return ListTile(
      leading: Icon(
        leadingIcon,
        color: Theme.of(Get.context!).textTheme.bodyLarge!.color,
      ),
      title: Text(
        title,
        style: regularDefault.copyWith(
          color: Theme.of(Get.context!).textTheme.bodyLarge!.color,
        ),
      ),
      trailing: Icon(
        Icons.arrow_forward_ios_rounded,
        size: Dimensions.space12,
        color: Theme.of(Get.context!).textTheme.bodyLarge!.color,
      ),
      onTap: onTap,
    );
  }
}
