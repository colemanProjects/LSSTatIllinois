#@author Neelan Coleman, development start date 03/13/2012
#information about Pyfits can be found at http://pythonhosted.org/pyfits/users_guide/users_tutorial.html
import Image
import numpy
import pyfits


print "fitsToTiff function is in test development stage"

#open contains a memmap=True option if the file is too large
hdulist = pyfits.open('testFitsFile_raft01_S00.fits')

#display info about the file
hdulist.info()

#get the image data from the primary hduObject (i.e. hdulist[0])
imageData = hdulist[0].data


#write that image data to file 
# http://old.nabble.com/Loading-Raw-Image-in-ImageMagicK-td25620081.html
outputImg = Image.fromarray(imageData)
outputImg.convert('RGB').save('out.png') 
histogram = outputImg.convert('RGB').histogram()
for pixel in histogram:
  print pixel

hdulist.close()




