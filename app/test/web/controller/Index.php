<?php
namespace app\test\web\controller;

use iris\Controller;

class Index extends Controller
{
    public function hello()
    {
        $content = "
2021上海车展期间，华为智能座舱“一芯多屏”解决方案首次亮相，其能够让座舱内的液晶仪表、AR-HUD（平视显示器）、中央显示、中央娱乐屏、中控屏、副驾屏等均由同一芯片提供性能支持。

日前，官方也带来了有关华为智能座舱“一芯多屏”解决方案的详细解读。

据悉，华为智能座舱“一芯多屏”解决方案采用了华为自研微内核架构（Micro Kernel）加轻量化图形栈，仪表域可实现低于3s的启动时间，起步无需等待。同时，基于华为达芬奇AI架构+软件信息安全防护（CC EAL 5+），其能够支持快速人脸识别，并基于Face ID关联个性化设置，自动调整座椅、方向盘等，提供主动式服务推荐。

自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读

 

在华为智能座舱“一芯多屏”解决方案当中，同一芯片可以为更多、更高分辨率的屏幕提供性能支持：当驾驶员需要导航时，副驾驶、后排乘客可以操控面前的屏幕并将导航结果实时同步给仪表屏，避免司机因操作屏幕而分心。

自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读

在芯片提供的算力和HOS高性能多媒体框架支持下，副驾驶和后排都可以观影、游戏。驾驶员开车时不用低头，即可从AR-HUD（汽车抬头/平视显示器）中获取速度、报警等信息。而且，仪表域和娱乐域基于鸿蒙车机操作系统进行异构多实例部署，即使娱乐系统产生异常故障，也不会影响仪表系统正常显示。

自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读

华为智能座舱“一芯多屏”解决方案支持多屏幕间低时延投射、切换。司机收到来电时，可以通过语音命令将高清视频通话实时同步至副驾或后排屏幕，而且支持“多模态语音智能识别”，其能够综合识别语音与唇形信息在嘈杂的环境中准确接收指令。

自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读

华为智能座舱“一芯多屏”解决方案还能通过人脸识别，对司机进行智能监控，实时检测驾驶员疲劳、分神状态。

自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读

按照官方的说法，华为智能座舱“一芯多屏”解决方案具备车规级（ASIL-D）自适应聚合引擎（ACE），提供原生的系统调用访问和中断处理能力，可将时延降低30%，同时为GPU提供确定性资源复用，保障仪表域任务的实时性和稳定性——行车时，仪表域高效稳定；驻车时，释放满血的算力给用户进行游戏等娱乐活动。
        ";
        $content = "<p>" . str_replace("\n", "</p><p>", $content). "</p>";
        $this->assign("title", "自研芯与鸿蒙加持！华为智能座舱“一芯多屏”最全解读");
        $this->assign("content", $content);
        $this->display("test/hello.html");
    }
}