<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/17 10:46
 */

namespace App\Service;

use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\SysRole;
use App\Model\SysUser;
use App\Model\SysUserDepartment;
use App\Model\SysUserRole;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Phper666\JWTAuth\JWT;

class UserService extends BaseService
{
    /**
     * @Inject
     * @var SysUser
     */
    protected $userModel;
    /**
     * @Inject
     * @var DepartmentService
     */
    protected $departmentService;
    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;

    public function login(string $username, string $password): array
    {
        $user = $this->userModel->findUserByWhere(['username' => $username], ['id', 'email', 'phone', 'username', 'nickname', 'realname', 'password', 'avatar', 'last_login_at', 'status']);
        if (!$user) {
            throw new BusinessException(ErrorCode::USER_NO_EXISTS);
        }
        if (!password_verify($password . config('pwd_salt'), $user->password)) {
            throw new BusinessException(ErrorCode::PASSWORD_ERROR);
        }
        if (!$user->status) {
            throw new BusinessException(ErrorCode::ACCOUNT_DISABLED);
        }
        $user->last_ip = request()->getServerParams()['remote_addr'];
        $user->last_login_at = date("Y-m-d H:i:s");
        $user->save();
        // 获取角色信息
        $roleIds = SysUserRole::query()->where('user_id', $user->id)->pluck('role_id');
        $roleInfo = SysRole::query()->whereIn('id', $roleIds)->select()->get()->toArray();
        $user->role_ids = array_column($roleInfo, 'id');
        $user->role_name = array_column($roleInfo, 'role_name');
        // 获取部门信息
        $departmentId = SysUserDepartment::where('user_id', $user->id)->value('department_id');
        $user->department_id = $departmentId;
        $user->access_token = (string)$this->jwt->getToken($user->toArray());
        $user->expire_token = $this->jwt->getTTL();
        unset($user->password);
        return $user->toArray();
    }

    /**
     * 添加用户
     * User：caogang
     * DateTime：2021/5/24 13:40
     * @param array $params
     * @return SysUser|\Hyperf\Database\Model\Model
     */
    public function addUser(array $params)
    {
        $params['uuid'] = uuid();
        $params['password'] = password_hash($params['password'] . config('pwd_salt'), PASSWORD_DEFAULT);
        $user = $this->userModel->findUserByWhere(['username' => $params['username']]);
        if ($user) {
            throw  new BusinessException(ErrorCode::USER_ALREADY_EXISTS);
        }
        return $this->userModel->create($params);
    }

    /**
     * 编辑用户
     * User：caogang
     * DateTime：2021/5/24 13:40
     * @param array $params
     * @return bool
     */
    public function updateUser(array $params): bool
    {
        if (isset($params['password']) && $params['password']) {
            $params['password'] = password_hash($params['password'] . config('pwd_salt'), PASSWORD_DEFAULT);
        }
        $where = [
            ['id', '!=', $params['id']],
            ['username', '=', $params['username']],
        ];
        $user = $this->userModel->findUserByWhere($where);
        if ($user) {
            throw  new BusinessException(ErrorCode::USER_ALREADY_EXISTS);
        }
        return $this->userModel->where('id', $params['id'])->update(SysUser::fillableFromArray($params));
    }

    /**
     * 禁用或启用员工
     * User：caogang
     * DateTime：2021/5/17 16:11
     */
    public function disable($id, $status): bool
    {
        return SysUser::where('id', $id)->update(['status' => $status]);
    }

    /**
     * 刪除員工
     * User：caogang
     * DateTime：2021/5/24 13:41
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return SysUser::destroy($id);
    }

    /**
     * 获取用户列表
     * User：caogang
     * DateTime：2021/5/17 11:29
     */
    public function getUserList($page, $size, $field, $realname, $username, $position, $departmentId): array
    {
        $list = $this->userModel->where('id', '!=', Common::ADMIN_ID)
            ->where(function ($query) use ($realname, $username, $position, $departmentId) {
                if ($realname) {
                    $query->where('realname', 'like', "%{$realname}%");
                }
                if ($username) {
                    $query->where('username', 'like', "%{$username}%");
                }
                if ($position) {
                    $query->where('position', 'like', "%{$position}%");
                }
                if ($departmentId) {
                    $departmentIds = $this->departmentService->getAllChildIds($departmentId);
                    $query->whereIn('department_id', $departmentIds);
                }
            })
            ->orderBy('id', 'desc')
            ->select($field)->paginate($size, $field, 'page', $page)->toArray();
        foreach ($list['data'] as &$v) {
            $roleIds = SysUserRole::where('user_id', $v['id'])->pluck('role_id')->toArray();
            $roleInfo = SysRole::whereIn('id', $roleIds)->get()->toArray();
            $v['role_name'] = array_column($roleInfo, 'role_name');
            $v['role_ids'] = array_column($roleInfo, 'id');
            $v['role_name'] = implode(',', $v['role_name']);
            if (!$roleInfo) {
                $v['role_ids'] = [0];
            }
        }
        return $list;
    }


    /**
     * User：caogang
     * DateTime：2021/5/24 11:44
     * @param array $params
     * @return bool
     */
    public function settingRole(array $params): bool
    {
        $roleIds = explode(',', trim($params['role_ids'], ','));
        DB::beginTransaction();
        try {
            // 删除用户-角色关系
            SysUserRole::query()->where('user_id', $params['user_id'])->delete();
            foreach ($roleIds as $v) {
                $data = [
                    'user_id' => $params['user_id'],
                    'role_id' => $v
                ];
                SysUserRole::query()->insert($data);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            p($e->getMessage(), ErrorCode::SETTING_ROLE_FAIL);
            return false;
        }
        return true;
    }
}