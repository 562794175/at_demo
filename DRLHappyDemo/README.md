
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