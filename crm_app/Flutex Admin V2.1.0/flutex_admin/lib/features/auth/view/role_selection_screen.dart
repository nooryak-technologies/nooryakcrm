import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:flutex_admin/core/route/route.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/images.dart';
import 'package:flutex_admin/core/utils/style.dart';

class RoleSelectionScreen extends StatelessWidget {
  const RoleSelectionScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              width: double.infinity,
              decoration: const BoxDecoration(
                color: ColorResources.appBarColor,
                image: DecorationImage(
                  alignment: Alignment.topCenter,
                  image: AssetImage(MyImages.login),
                  fit: BoxFit.fitWidth,
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Padding(
                    padding: const EdgeInsets.only(
                      top: 120.0,
                      bottom: 20.0,
                    ),
                    child: Center(
                      child: Image.asset(
                        MyImages.appLogo,
                        height: 110,
                      ),
                    ),
                  ),
                  SizedBox(
                    width: MediaQuery.sizeOf(context).width,
                    child: Padding(
                      padding: const EdgeInsets.symmetric(
                        horizontal: Dimensions.space30,
                        vertical: Dimensions.space20,
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Choose Your Role",
                            style: mediumMegaLarge.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: Dimensions.space5),
                          Text(
                            "Please select how you want to access your dashboard.",
                            style: regularDefault.copyWith(
                              color: Colors.white.withOpacity(0.9),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  Container(
                    decoration: BoxDecoration(
                      color: Theme.of(context).scaffoldBackgroundColor,
                      borderRadius: const BorderRadius.only(
                        topLeft: Radius.circular(20),
                        topRight: Radius.circular(20),
                      ),
                    ),
                    padding: const EdgeInsets.symmetric(
                      horizontal: Dimensions.space20,
                      vertical: Dimensions.space30,
                    ),
                    child: Column(
                      children: [
                        _buildRoleCard(
                          context,
                          title: "Company",
                          subtitle: "Access features as a Company representative",
                          icon: Icons.business_rounded,
                          onPressed: () {
                            Get.toNamed(RouteHelper.loginScreen, arguments: "Company");
                          },
                        ),
                        const SizedBox(height: Dimensions.space20),
                        _buildRoleCard(
                          context,
                          title: "Staff",
                          subtitle: "Access features as a Staff team member",
                          icon: Icons.badge_rounded,
                          onPressed: () {
                            Get.toNamed(RouteHelper.loginScreen, arguments: "Staff");
                          },
                        ),
                        const SizedBox(height: Dimensions.space40),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRoleCard(
    BuildContext context, {
    required String title,
    required String subtitle,
    required IconData icon,
    required VoidCallback onPressed,
  }) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    
    return InkWell(
      onTap: onPressed,
      borderRadius: BorderRadius.circular(Dimensions.space15),
      child: Container(
        padding: const EdgeInsets.symmetric(
          horizontal: Dimensions.space20,
          vertical: Dimensions.space25,
        ),
        decoration: BoxDecoration(
          color: isDark ? ColorResources.cardColorDark : Colors.white,
          borderRadius: BorderRadius.circular(Dimensions.space15),
          border: Border.all(
            color: ColorResources.primaryColor.withOpacity(0.2),
            width: 1.5,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(Dimensions.space12),
              decoration: BoxDecoration(
                color: ColorResources.primaryColor.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(
                icon,
                color: ColorResources.primaryColor,
                size: 32,
              ),
            ),
            const SizedBox(width: Dimensions.space20),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: semiBoldLarge.copyWith(
                      color: isDark ? Colors.white : ColorResources.primaryTextColor,
                    ),
                  ),
                  const SizedBox(height: Dimensions.space5),
                  Text(
                    subtitle,
                    style: regularSmall.copyWith(
                      color: isDark
                          ? Colors.white.withOpacity(0.6)
                          : ColorResources.contentTextColor,
                    ),
                  ),
                ],
              ),
            ),
            Icon(
              Icons.arrow_forward_ios_rounded,
              color: ColorResources.primaryColor.withOpacity(0.7),
              size: 16,
            ),
          ],
        ),
      ),
    );
  }
}
