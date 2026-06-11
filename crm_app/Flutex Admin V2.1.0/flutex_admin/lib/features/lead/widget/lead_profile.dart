import 'package:flutex_admin/common/components/divider/custom_divider.dart';
import 'package:flutex_admin/common/components/text/text_icon.dart';
import 'package:flutex_admin/core/helper/date_converter.dart';
import 'package:flutex_admin/core/helper/string_format_helper.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/lead/model/lead_details_model.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class LeadProfile extends StatelessWidget {
  const LeadProfile({
    super.key,
    required this.leadModel,
  });
  final LeadDetails leadModel;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(Dimensions.space10),
      child: Column(
        children: [
          // ── Header Card ──────────────────────────────────────
          Container(
            padding: const EdgeInsets.all(Dimensions.space15),
            decoration: BoxDecoration(
              color: Theme.of(context).cardColor,
              borderRadius: const BorderRadius.all(
                Radius.circular(Dimensions.cardRadius),
              ),
              boxShadow: [
                BoxShadow(
                  color: Theme.of(context).shadowColor.withValues(alpha: 0.05),
                  spreadRadius: 1,
                  blurRadius: 4,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            child: Column(
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            '${leadModel.name}',
                            overflow: TextOverflow.ellipsis,
                            maxLines: 2,
                            style: Theme.of(context).textTheme.bodyLarge,
                          ),
                          Text(
                            '${leadModel.title}',
                            overflow: TextOverflow.ellipsis,
                            maxLines: 1,
                            style: lightSmall.copyWith(
                                color: ColorResources.blueGreyColor),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: Dimensions.space10),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text(
                          leadModel.leadValue ?? '-',
                          style: regularDefault,
                          overflow: TextOverflow.ellipsis,
                        ),
                        Text(
                          leadModel.sourceName ?? '-',
                          style: lightSmall,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ],
                ),
                const CustomDivider(space: Dimensions.space10),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextIcon(
                      text: leadModel.statusName ?? '',
                      icon: Icons.check_circle_outline_rounded,
                      iconColor:
                          Converter.hexStringToColor(leadModel.color ?? ''),
                    ),
                    TextIcon(
                      text: DateConverter.formatValidityDate(
                          leadModel.dateAdded ?? ''),
                      icon: Icons.calendar_month,
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: Dimensions.space20),

          // ── Company / VAT / Phone / Website Card ─────────────
          Container(
            padding: const EdgeInsets.all(Dimensions.space15),
            decoration: BoxDecoration(
              color: Theme.of(context).cardColor,
              borderRadius: const BorderRadius.all(
                Radius.circular(Dimensions.cardRadius),
              ),
              boxShadow: [
                BoxShadow(
                  color: Theme.of(context).shadowColor.withValues(alpha: 0.05),
                  spreadRadius: 1,
                  blurRadius: 4,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Text(LocalStrings.company.tr, style: lightDefault),
                    ),
                    Expanded(
                      child: Text(
                        LocalStrings.vatNumber.tr,
                        style: lightDefault,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        leadModel.company ?? '-',
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    Expanded(
                      child: Text(
                        leadModel.vat ?? '-',
                        overflow: TextOverflow.ellipsis,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                const CustomDivider(),
                Row(
                  children: [
                    Expanded(
                      child: Text(LocalStrings.phone.tr, style: lightDefault),
                    ),
                    Expanded(
                      child: Text(
                        LocalStrings.website.tr,
                        style: lightDefault,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        leadModel.phoneNumber ?? '-',
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    Expanded(
                      child: Text(
                        leadModel.website ?? '-',
                        overflow: TextOverflow.ellipsis,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: Dimensions.space20),

          // ── Address Card ──────────────────────────────────────
          Container(
            padding: const EdgeInsets.all(Dimensions.space15),
            decoration: BoxDecoration(
              color: Theme.of(context).cardColor,
              borderRadius: const BorderRadius.all(
                Radius.circular(Dimensions.cardRadius),
              ),
              boxShadow: [
                BoxShadow(
                  color: Theme.of(context).shadowColor.withValues(alpha: 0.05),
                  spreadRadius: 1,
                  blurRadius: 4,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(LocalStrings.address.tr),
                Text(
                  leadModel.address ?? '-',
                  overflow: TextOverflow.ellipsis,
                  maxLines: 3,
                ),
                const CustomDivider(),
                Row(
                  children: [
                    Expanded(child: Text(LocalStrings.city.tr)),
                    Expanded(
                      child: Text(
                        LocalStrings.state.tr,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        leadModel.city ?? '-',
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    Expanded(
                      child: Text(
                        leadModel.state ?? '-',
                        overflow: TextOverflow.ellipsis,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                const CustomDivider(),
                Row(
                  children: [
                    Expanded(child: Text(LocalStrings.zipCode.tr)),
                    Expanded(
                      child: Text(
                        LocalStrings.country.tr,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        leadModel.zip ?? '-',
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    Expanded(
                      child: Text(
                        leadModel.country ?? '-',
                        overflow: TextOverflow.ellipsis,
                        textAlign: TextAlign.end,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
