import 'package:flutex_admin/core/service/api_service.dart';
import 'package:flutex_admin/core/utils/method.dart';
import 'package:flutex_admin/core/utils/url_container.dart';
import 'package:flutex_admin/common/models/response_model.dart';

class ItemRepo {
  ApiClient apiClient;
  ItemRepo({required this.apiClient});

  Future<ResponseModel> getAllItems() async {
    String url = "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}";
    ResponseModel responseModel =
        await apiClient.request(url, Method.getMethod, null, passHeader: true);
    return responseModel;
  }

  Future<ResponseModel> getItemDetails(itemId) async {
    String url = "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}/id/$itemId";
    ResponseModel responseModel =
        await apiClient.request(url, Method.getMethod, null, passHeader: true);
    return responseModel;
  }

  Future<ResponseModel> searchItem(keysearch) async {
    String url =
        "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}/search/$keysearch";
    ResponseModel responseModel =
        await apiClient.request(url, Method.getMethod, null, passHeader: true);
    return responseModel;
  }

  Future<ResponseModel> createItem(
    Map<String, dynamic> params, {
    String? itemId,
    bool isUpdate = false,
  }) async {
    String url = isUpdate
        ? "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}/id/$itemId"
        : "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}";
    ResponseModel responseModel = await apiClient.request(
      url,
      isUpdate ? Method.putMethod : Method.postMethod,
      params,
      passHeader: true,
    );
    return responseModel;
  }

  Future<ResponseModel> deleteItem(itemId) async {
    String url = "${UrlContainer.baseUrl}${UrlContainer.itemsUrl}/id/$itemId";
    ResponseModel responseModel = await apiClient.request(
      url,
      Method.deleteMethod,
      null,
      passHeader: true,
    );
    return responseModel;
  }

  Future<ResponseModel> getItemGroups() async {
    String url = "${UrlContainer.baseUrl}${UrlContainer.miscellaneousUrl}/item_groups";
    ResponseModel responseModel = await apiClient.request(
      url,
      Method.getMethod,
      null,
      passHeader: true,
    );
    return responseModel;
  }
}
