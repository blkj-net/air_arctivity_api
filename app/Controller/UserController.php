<?php
/**
 * 用户管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/12 14:35
 */

namespace App\Controller;

use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\SystemLogRecordService;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Phper666\JWTAuth\JWT;

class UserController extends BaseController
{
    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Inject
     * @var SystemLogRecordService
     */
    protected $systemLogRecordService;

    /**
     * 登录
     * User：caogang
     * DateTime：2021/5/12 10:50
     */
    public function login()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => '请输入用户名',
            'password.required' => '请输入密码',
        ]);
        $user = $this->userService->login($params['username'], $params['password']);
        return $this->success($user);
    }

    /**
     * 退出登录
     * User：caogang
     * DateTime：2021/5/13 13:11
     */
    public function logout(Jwt $jwt)
    {
        $jwt->logout(request()->userInfo['token']);
        return $this->success();
    }

    /**
     * 添加用户
     * User：caogang
     * DateTime：2021/5/12 14:52
     * @return array
     */
    public function create()
    {
        $params = $this->request->all();
        $this->validatorParams($params, [
            'realname'      => 'required',
            'username'      => 'required',
            'password'      => 'required',
            'department_id' => 'required',
            'position'      => 'required'
        ], [
            'realname.required'      => '请输入名称',
            'username.required'      => '请输入账号',
            'password.required'      => '请输入密码',
            'department_id.required' => '请选择部门',
            'position,required'      => '岗位不能为空'
        ]);
        $ret = $this->userService->addUser($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 获取用户列表
     * User：caogang
     * DateTime：2021/5/14 11:30
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $realname = request()->input('realname');
        $username = request()->input('username');
        $position = request()->input('position');
        $departmentId = request()->input('department_id');
        $field = ['realname', 'id', 'uuid', 'username', 'password', 'avatar', 'last_login_at', 'status', 'department_id', 'last_ip', 'created_at', 'position'];
        $list = $this->userService->getUserList($page, $size, $field, $realname, $username, $position, $departmentId);
        return $this->success($list);
    }


    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'            => ['required', 'numeric'],
            'realname'      => 'required',
            'username'      => 'required',
            'department_id' => 'required',
            'position'      => 'required'
        ], [
            'realname.required'      => '请输入姓名',
            'username.required'      => '请输入用户名',
            'department_id.required' => '请选择部门',
            'position,required'      => '岗位不能为空'
        ]);
        $ret = $this->userService->updateUser($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 获取用户信息
     * User：caogang
     * DateTime：2021/5/18 15:11
     */
    public function info()
    {
        $userInfo = request()->userInfo;
        $userInfo['roles'] = ['admin'];
        $userInfo['avatar'] = 'https://i.gtimg.cn/club/item/face/img/2/15922_100.gif';
        return $this->success($userInfo);
    }

    /**
     * 禁用员工
     * User：caogang
     * DateTime：2021/5/17 16:09
     * @return array
     */
    public function disable()
    {
        $id = request()->input('id');
        $status = request()->input('status');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        if (!in_array($status, [0, 1])) {
            return $this->error(ErrorCode::PARAMS_INVALID);
        }
        $ret = $this->userService->disable($id, $status);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

    public function delete()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $ret = $this->userService->delete($id);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

    /**
     * 分配角色
     * User：caogang
     * DateTime：2021/5/19 22:02
     */
    public function settingRole()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'user_id'  => ['required', 'numeric'],
            'role_ids' => 'required'
        ]);
        $ret = $this->userService->settingRole($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

}
