
import os
import tensorflow as tf
import numpy as np
from tensorflow.examples.tutorials.mnist import input_data


# ��������
mnist = input_data.read_data_sets("MNIST_data/", one_hot=True)



trX, trY, teX, teY = mnist.train.images, mnist.train.labels, mnist.test.images,mnist.test.labels
X = tf.placeholder("float", [None, 784])
Y = tf.placeholder("float", [None, 10])

# ����Ȩ�غ���
def init_weights(shape):
    return tf.Variable(tf.random_normal(shape, stddev=0.01))

# ��ʼ��Ȩ�ز���
w_h = init_weights([784, 625])
w_h2 = init_weights([625, 625])
w_o = init_weights([625, 10])


# ����ģ��
def model(X, w_h, w_h2, w_o, p_keep_input, p_keep_hidden):
    # ��һ��ȫ���Ӳ�
    X = tf.nn.dropout(X, p_keep_input)
    h = tf.nn.relu(tf.matmul(X, w_h))
    h = tf.nn.dropout(h, p_keep_hidden)
    # �ڶ���ȫ���Ӳ�
    h2 = tf.nn.relu(tf.matmul(h, w_h2))
    h2 = tf.nn.dropout(h2, p_keep_hidden)
    return tf.matmul(h2, w_o) #���Ԥ��ֵ


p_keep_input = tf.placeholder("float")
p_keep_hidden = tf.placeholder("float")
py_x = model(X, w_h, w_h2, w_o, p_keep_input, p_keep_hidden)


cost = tf.reduce_mean(tf.nn.softmax_cross_entropy_with_logits(py_x, Y))
train_op = tf.train.RMSPropOptimizer(0.001, 0.9).minimize(cost)
predict_op = tf.argmax(py_x, 1)

ckpt_dir = "./ckpt_dir"
if not os.path.exists(ckpt_dir):
    os.makedirs(ckpt_dir)

# ��������������������trainable=False������Ҫ��ѵ��
global_step = tf.Variable(0, name='global_step', trainable=False)


# �����������б����󣬵���tf.train.Saver
saver = tf.train.Saver()
# λ��tf.train.Saver֮��ı��������ᱻ�洢
non_storable_variable = tf.Variable(777)

with tf.Session() as sess:
    tf.initialize_all_variables().run()
    start = global_step.eval() # �õ�global_step�ĳ�ʼֵ
    print("Start from:", start)
    for i in range(start, 100):
        # ��128��Ϊbatch_size
        for start, end in zip(range(0, len(trX), 128), range(128, len(trX)+1, 128)):
            sess.run(train_op, feed_dict={X: trX[start:end], Y: trY[start:end],
            p_keep_input: 0.8, p_keep_hidden: 0.5})
            global_step.assign(i).eval() # ���¼�����
            saver.save(sess, ckpt_dir + "/model.ckpt", global_step=global_step) # �洢ģ��
            
with tf.Session() as sess:
    tf.initialize_all_variables().run()
    ckpt = tf.train.get_checkpoint_state(ckpt_dir)
    if ckpt and ckpt.model_checkpoint_path:
        print(ckpt.model_checkpoint_path)
        saver.restore(sess, ckpt.model_checkpoint_path) # �������еĲ���
        # �����￪ʼ�Ϳ���ֱ��ʹ��ģ�ͽ���Ԥ�⣬���߽��ż���ѵ����
        
v = tf.Variable(0, name='my_variable')
sess = tf.Session()
tf.train.write_graph(sess.graph_def, '/tmp/tfmodel', 'train.pbtxt')


with tf.Session() as _sess:
    with gfile.FastGFile("/tmp/tfmodel/train.pbtxt",'rb') as f:
        graph_def = tf.GraphDef()
        _sess.graph.as_default()
        tf.import_graph_def(graph_def,name='tfgraph')



