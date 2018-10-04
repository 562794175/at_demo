watch -n 1 -d nvidia-smi 


成功解决:
/root/anaconda3/lib/python3.6/site-packages/h5py/__init__.py:36: FutureWarning: Conversion of the second argument of issubdtype from `float` to `np.floating` is deprecated. In future, it will be treated as `np.float64 == np.dtype(float).type`.
  from ._conv import register_converters as _register_converters

https://blog.csdn.net/qq_41185868/article/details/80276847
解决思路
包内出错，是h5py包

解决办法
对h5py进行更新升级
pip install h5py==2.8.0rc1
-----------------------------------------------------------------------------------------------

libpng warning: iCCP: known incorrect sRGB profile


------------------------------------------------------------------------------------------------
http://www.icare.univ-lille1.fr/wiki/index.php/How_to_convert_a_matplotlib_figure_to_a_numpy_array_or_a_PIL_image

https://stackoverflow.com/questions/7821518/matplotlib-save-plot-to-numpy-array

------------------------------------------------------------------------------------------------

find . -name "*.png" -type f -print -exec rm -f {} \;

------------------------------------------------------------------------------------------------

使用Python matplotlib绘制股票走势图
https://www.jdon.com/idea/matplotlib.html
教程入门：建立一个完全自动化的交易系统
https://www.jdon.com/idea/getting-started-building-a-fully-automated-trading-system.html

Faster_RCNN 1.准备工作
https://www.cnblogs.com/king-lps/p/8975950.html

------------------------------------------------------------------------------------------------
Process finished with exit code 139 (interrupted by signal 11: SIGSEGV)

pycharm中使用matplotlib.pyplot 绘图时报错
https://blog.csdn.net/qq_24699959/article/details/80909445
我发现有时候pip或者conda安装了工具包，但是pycharm还是识别不到，按照如下操作 会有奇效

------------------------------------------------------------------------------------------------
matplotlib为图片上添加触发事件进行交互
https://blog.csdn.net/qq_30490125/article/details/53783129

Set properties for specific patch in matplotlib.collections.PathCollection
https://stackoverflow.com/questions/37309578/set-properties-for-specific-patch-in-matplotlib-collections-pathcollection
------------------------------------------------------------------------------------------------
matlab gui 随鼠标移动的十字线老是闪烁呢，感觉刷新率不够
http://www.ilovematlab.cn/thread-213668-1-1.html

MATLAB动态绘图笔记
http://blog.sina.com.cn/s/blog_4c2e13ff0101a9ft.html

Matlab使用Plot函数实现数据动态显示方法总结
https://blog.csdn.net/u013468614/article/details/58678500

【Python】【matplotlib】键鼠响应事件
https://blog.csdn.net/guofei9987/article/details/78106492

Matplotlib.pyplot 常用方法（一）
https://blog.csdn.net/sinat_34022298/article/details/76348969

rcParams